<?php

namespace App;

use App\SubPage;
use App\StaticPage;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Utility extends Model
{
    public static function generate_menu()
    {
        $pages = StaticPage::all();

        $pages = $pages->transform(
            function ($page, $key) {
                $page->slug = $page->slug->slug;

                $sub_pages = $page->sub_pages;
                $page->sub_pages = $sub_pages->transform(
                    function ($sub, $key) {
                        $has_slug = isset($sub->slug->slug);
                        if ($has_slug) {
                            $sub->slug = $sub->slug->slug;
                        } else {
                            $slug = collect();
                            $slug->slug = 'nessuno';
                            $sub->slug = $slug;
                        }
                        return $sub;
                    }
                );

                return $page;
            }
        );

        return $pages;
    }


    public static function save_image($file)
    {
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
        $img_landscape = Image::make($path.'/landscape_'.$filename)->resize(
            1920, 1080, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            }
        )->save();

        $media = new Media;
        $media->src = Storage::disk('local')->url($src);
        $media->thumb = Storage::disk('local')->url($thumbnail);
        $media->thumb_md = Storage::disk('local')->url($thumbnail_md);
        $media->landscape = Storage::disk('local')->url($landscape);
        $media->save();

        return $media;
    }

    public static function format_complex_modules($modules, $has_json = true)
    {
        return $modules->transform(
            function ($module, $key) use ($has_json) {
                return Utility::format_complex_module($module, $has_json);
            }
        )->all();
    }

    public static function format_complex_module($module, $has_json)
    {
        if ($module->type == 'grid') {
            $content = json_decode($module->content);
            $grid_options = isset($content->options) ? json_encode($content->options) : '';
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
                        'type_id' => $block->id,
                        'width' => $options->width,
                        'height' => $options->height,
                        'bgColor' => $options->color,
                        'thumb' => $options->thumb,
                        'created_at' => $block->created_at,
                    ];

                if (isset($options->x)) {
                    $grid_block['x'] = $options->x;
                }

                if (isset($options->y)) {
                    $grid_block['y'] = $options->y;
                }

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
                    $grid_block['thumb'] = $block->thumb;
                    $grid_block['content'] = $has_json ? json_encode($block) : $block;
                    break;

                case 'news':
                    $block->slug = $block->slug;
                    $block->category = $block->category;
                    $grid_block['thumb'] = $block->thumb;
                    $grid_block['content'] = $has_json ? json_encode($block) : $block;
                    break;

                default:
                    $block->slug = $block->slug;
                    $grid_block['content'] = $has_json ? json_encode($block) : $block;
                    // dd($grid_block['content']);
                    break;
                }
                array_push($blocks, $grid_block);
            }

            $data = [
                    'blocks' => $blocks,
                    'type' => $grid->type,
                    'options' => $grid_options,
                    'title' => $grid->title,
                ];

            $module->content = $has_json ? json_encode($data) : $data;

        } elseif ($module->type == 'row') {
            $columns = $module->modules()->with('modules')->get();

            $columns = $columns->transform(
                function ($column, $key) use ($has_json) {
                    $column->content = json_decode($column->content);
                    if ($has_json) {
                        $column->content->modules = $column->modules;
                        $column->content = json_encode($column->content);
                    } else {
                        $modules = $column->modules;
                        $column->content->modules = $modules->transform(
                            function ($module, $key) {
                                $module->content = json_decode($module->content);
                                return $module;
                            }
                        )->all();
                    }
                    return $column;
                }
            )->all();

            $row = [
                'id' => $module->id,
                'columns' => $columns,
            ];

            $module->content = $has_json ? json_encode($columns) : $columns;
        } else {
            // trasforma il contenuto in oggetto oppure lo lascia in json
            $module->content = $has_json ? $module->content : json_decode($module->content);
        }

        // dd($module);
        return $module;
    }
}
