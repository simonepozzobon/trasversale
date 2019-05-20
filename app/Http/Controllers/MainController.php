<?php

namespace App\Http\Controllers;

use App\Slug;
use App\Grid;
use App\Module;
use App\SubPage;
use App\Utility;
use App\StaticPage;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home() {
        return view('welcome');
    }

    public function test() {
        $test = $this->get_dynamic_item('notizie', 'modello-trento', null);
        dd($test);
    }

    public function get_dynamic_item($page = null, $subpage = null, $slug = null) {
        if ($slug) {
            $item = Slug::where([
                ['slug', '=', $slug],
                ['sluggable_type', '!=', 'App\\StaticPage'],
                ['sluggable_type', '!=', 'App\\SubPage'],
            ])->with('sluggable.modules')->first();
        }

        else if ($subpage) {
            $item = Slug::where([
                ['slug', '=', $subpage],
                ['sluggable_type', '=', 'App\\SubPage'],
            ])->orWhere([
                ['slug', '=', $subpage],
                ['sluggable_type', '=', 'App\\News'],
            ])->with('sluggable.modules')->first();
        }

        else {
            $item = Slug::where([
                ['slug', '=', $page],
                ['sluggable_type', '=', 'App\\StaticPage'],
            ])->with('sluggable.modules')->first();
        }

        if ($item) {
            if ($item->sluggable->modules->count() > 0) {
                $item->sluggable->modules = $this->format_complex_modules($item->sluggable->modules);
            }

            $item->sluggable->model = $this->stringify_class($item->sluggable);


            return [
                'success' => true,
                'debug' => $this->stringify_class($item->sluggable),
                'item' => $item->sluggable,
            ];
        }
        return [
            'success' => false,
        ];
    }

    public function get_static_page($static_page) {
        $slug = Slug::where('slug', $static_page)->with('sluggable')->first();
        $page = $slug->sluggable;
        return $page;
    }

    public function get_sub_page($sub_page) {

        $slug = Slug::where('slug', $sub_page)->with('sluggable')->first();
        $sub = $slug->sluggable()->with('page.slug', 'modules')->first();
        $sub->page = $sub->page;
        $sub->modules = $sub->modules;
        return view('sub-page', compact('sub'));

    }

    public function dummy_page() {
        $pages = Collect();
        return true;
    }

    public function format_complex_modules($modules) {
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
                                $grid_block['content'] = $block[$options->content];
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

    public function stringify_class($item) {
        $string = get_class($item);
        $string = str_replace('App\\', '', $string);
        $string = strtolower($string);
        return $string;
    }

}
