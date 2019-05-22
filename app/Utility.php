<?php

namespace App;

use App\SubPage;
use App\StaticPage;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Utility extends Model
{
    public static function generate_menu() {
        $pages = StaticPage::all();

        $pages = $pages->transform(function($page, $key) {
            $page->slug = $page->slug->slug;

            $sub_pages = $page->sub_pages;
            $page->sub_pages = $sub_pages->transform(function($sub, $key) {
                $sub->slug = $sub->slug->slug;
                return $sub;
            });

            return $page;
        });

        return $pages;
    }


    public static function save_image($file) {
        $filename = uniqid().'.'.$file->getClientOriginalExtension();
        $src = $file->storeAs('public/media', $filename);

        // Pre-save files to be resized
        $thumbnail = $file->storeAs('public/media', 'thumb_'.$filename);
        $thumbnail_md = $file->storeAs('public/media', 'thumb_md_'.$filename);
        $landscape = $file->storeAs('public/media', 'landscape_'.$filename);

        // Path where files are stored according to Filesystem.php
        $path = storage_path('app/public/media');

        // Edit files
        $img_thumbnail = Image::make($path.'/thumb_'.$filename)->fit(150)->save();
        $img_thumbnail_md = Image::make($path.'/thumb_md_'.$filename)->fit(400)->save();
        $img_landscape = Image::make($path.'/landscape_'.$filename)->resize( 1920, null, function ($constraint) {
            $constraint->aspectRatio(); $constraint->upsize();
        })->save();

        $media = new Media;
        $media->src = Storage::disk('local')->url($src);
        $media->thumb = Storage::disk('local')->url($thumbnail);
        $media->thumb_md = Storage::disk('local')->url($thumbnail_md);
        $media->landscape = Storage::disk('local')->url($landscape);
        $media->save();

        return $media;
    }

    public static function format_complex_modules($modules) {
        return $modules->transform(function($module, $key) {

            if ($module->type == 'grid') {
                    $content = json_decode($module->content);
                    $grid_id = $content->id;

                    $grid = Grid::with('elements.elementable')->find($grid_id);
                    $blocks = array();

                    foreach ($grid->elements as $key => $element) {
                        $block = $element->elementable;
                        $type = strtolower(str_replace('App\\', '', get_class($block)));
                        $options = json_decode($element->options);

                        $grid_block = [
                            'id' => $element->id,
                            'type' => $type,
                            'width' => $options->width,
                            'height' => $options->height,
                            'bgColor' => $options->color,
                            'img' => $options->img,
                            'created_at' => $block->created_at,
                        ];

                        switch ($type) {
                            case 'module':
                                $sub_module = json_decode($block->content);
                                $grid_block['sub_type'] = $block->type;
                                $grid_block['bgColor'] = $sub_module->bg_color;
                                $grid_block['color'] = $sub_module->color;
                                $grid_block['content'] = $sub_module->content;
                                break;

                            case 'product':
                                $block->slug = $block->slug;
                                $block->category = $block->category;
                                $grid_block['img'] = $block->thumb;
                                $grid_block['content'] = json_encode($block);
                                break;

                            case 'news':
                                $block->slug = $block->slug;
                                $block->category = $block->category;
                                $grid_block['img'] = $block->thumb;
                                $grid_block['content'] = json_encode($block);
                                break;

                            default:
                                $block->slug = $block->slug;
                                $grid_block['content'] = json_encode($block);
                                // dd($grid_block['content']);
                                break;
                        }
                        array_push($blocks, $grid_block);
                    }

                    $data = [
                        'blocks' => $blocks,
                        'type' => $grid->type,
                        'options' => $grid->options,
                    ];
                    $module->content = json_encode($data);
            }

            else if ($module->type == 'row') {
                $columns = $module->modules()->with('modules')->get();
                $row = [
                    'id' => $module->id,
                    'columns' => $columns,
                ];

                $module->content = json_encode($columns);
            }

            return $module;
        })->all();
    }
}
