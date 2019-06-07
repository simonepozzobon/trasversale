<?php

namespace App\Http\Controllers;

use App\Post;
use App\News;
use App\Grid;
use App\Module;
use App\Product;
use App\Element;
use App\Utility;
use App\SubPage;
use App\StaticPage;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function test()
    {
        $request = new Request();
        $request->replace(
            [
                "id" => "10",
                "type" => "title",
                "modulable_type" => "App\\StaticPage",
                "modulable_id" => "2",
                "content" => "{\"content\":\"Giuseppe\",\"fontSize\":\"h2\",\"color\":null,\"isColumn\":null,\"uppercase\":null}",
                "created_at" => "2019-06-06 00:35:30",
                "updated_at" => "2019-06-06 00:35:30",
                "uuid" => "5qngii0q09",
                "isNew" => "false"
            ]
        );
        return $this->save_component($request);
        // return $this->get_page(2);

        // dd($result->modules);
        // $module = Module::find($request->id);
        // $module = $this->set_module($module, $request);
        //
        // $this->get_grid_elements('products');
    }

    public function index($slug = null)
    {
        $pages = Utility::generate_menu();
        return view('admin.index', compact('pages'));
    }

    public function get_grid_elements($type)
    {
        $elements = array();

        switch ($type) {
        case 'news':
            $news = News::with('slug', 'category')->get();
            $elements = $this->uniform_and_merge($news);
            break;

        case 'products':
            $products = Product::with('slug', 'category')->get();
            $elements = $this->uniform_and_merge($products);
            break;

        case 'last-mix':
            $products = Product::with('slug', 'category')->get();
            $news = News::with('slug', 'category')->get();
            $elements = $this->uniform_and_merge($news, $products);
            break;

        default:
            $posts = Post::with('slug')->get();
            $news = News::with('slug', 'category')->get();
            $products = Product::with('slug', 'category')->get();
            $elements = $this->uniform_and_merge($posts, $news, $products);
            break;
        }

        return [
            'success' => true,
            'elements' => $elements,
        ];
    }

    public function get_post_type($type)
    {
        switch ($type) {
        case 'news':
            $elements = News::with('slug', 'category')->get();
            break;

        case 'products':
            $elements = Product::with('slug', 'category')->get();
            break;

        default:
            // code...
            break;
        }

        return [
            'success' => true,
            'elements' => $elements,
        ];
    }

    public function delete_post_type($type, $id)
    {
        switch ($type) {
        case 'news':
            $element = News::find($id);
            break;

        case 'products':
            $element = Product::find($id);
            break;
        }

        $element->delete();

        return [
            'success' => true,
            'element' => $element,
        ];
    }

    public function purge_request($request)
    {
        $temp = $request->all();
        $isNew = $temp['isNew'];

        if ($isNew === 'false' || $isNew === 0 || $isNew === false) {
            $temp['isNew'] = false;
        } else {
            $temp['isNew'] = true;
        }

        $request->merge(['isNew' => $temp['isNew']]);
        return $request;
    }

    public function save_component(Request $request)
    {
        $request = $this->purge_request($request);
        if ($request->isNew) {
            $module = new Module();
        } else {
            $module = Module::find($request->id);
        }

        $module = $this->update_module($module, $request);
        $module = Utility::format_complex_module($module, false);

        return [
            'success' => true,
            'module' => $module,
        ];
    }

    public function delete_component($id)
    {
        $module = Module::find($id);
        $module->delete();

        return [
            'success' => true,
            'module' => $module,
        ];
    }

    public function get_sub_page($id)
    {
        $page = SubPage::find($id);
        $page->modules = Utility::format_complex_modules($page->modules()->get(), false);
        return $page;
    }

    public function get_page($id)
    {
        $page = StaticPage::find($id);
        $page->modules = Utility::format_complex_modules($page->modules()->get(), false);
        return $page;
    }

    public function uniform_and_merge()
    {
        $collections = func_get_args();
        $merged = collect();

        foreach ($collections as $key => $collection) {
            $uniformed = $this->uniform_models($collection)->all();
            $merged = $merged->concat($uniformed);
        }

        $sorted = $merged->sortBy('updated_at')->values();
        return $sorted;
    }

    public function uniform_models($collection)
    {
        $formatted = $collection->transform(
            function ($item, $key) {
                $model = strtolower(str_replace('App\\', '', get_class($item)));
                $item->type = $model;

                // debug
                $item->selected = 0;
                return $item;
            }
        );
        return $collection;
    }

    public function update_module($module, $request)
    {
        $content = $request->content;
        if ($request->type == 'grid') {
            $content = json_decode($content);
            $grid_content = json_decode($module->content);

            $grid = isset($content->id) ? Grid::find($content->id) : new Grid();

            $grid->title = $content->title;
            $grid->type = $content->type;
            $grid->save();

            foreach ($content->blocks as $key => $block) {
                $model = 'App\\'.ucfirst($block->type);

                $options = [
                    'x' => $block->x,
                    'y' => $block->y,
                    'width' => $block->w,
                    'height' => $block->h,
                    'color' => 'primary',
                    'content' => 'title',
                    'thumb' => isset($block->thumb) ? $block->thumb : '',
                ];

                // dd($block);
                $has_block = isset($block->id);
                if ($has_block) {
                    $element = Element::find($block->id);
                    $element->delete();
                    $element = new Element();
                } else {
                    $element = new Element();
                }

                $element->grid_id = $grid->id;
                $element->elementable_type = $model;
                $element->elementable_id = $block->type_id;
                $element->options = json_encode($options);
                $element->save();
            }

            $content = json_encode(
                [
                    'id' => $grid->id
                ]
            );

        } elseif ($request->type == 'image' && $request->hasFile('file')) {
            $file = $request->file('file');
            $media = Utility::save_image($file);

            $content = json_decode($content);
            $content->src = $media->landscape;
            $content = json_encode($content);

        }

        $module->type = $request->type;
        $module->order = $request->order;
        $module->modulable_type = $request->modulable_type;
        $module->modulable_id = $request->modulable_id;
        $module->content = $content;
        $module->save();

        return $module;
    }

    public function set_module($module, $request)
    {
        $content = $request->data;

        if ($request->module == 'grid') {
            $content = json_decode($request->data);

            $grid_content = json_decode($module->content);
            $hasGrid = isset($grid_content->id);

            if ($hasGrid) {
                $grid = Grid::find($grid_content->id);
            } else {
                $grid = new Grid();
            }

            $grid->title = $content->title;
            $grid->type = $content->type;
            $grid->save();

            foreach ($content->elements->blocks as $key => $block) {
                $model = 'App\\'.ucfirst($block->type);

                $options = [
                    'x' => $block->x,
                    'y' => $block->y,
                    'width' => $block->w,
                    'height' => $block->h,
                    'color' => 'primary',
                    'content' => 'title',
                    'thumb' => isset($block->thumb) ? $block->thumb : '',
                ];

                // dd($block);
                $has_block = isset($block->id);
                if ($has_block) {
                    $element = Element::find($block->id);
                    $element->delete();
                    $element = new Element();
                } else {
                    $element = new Element();
                }

                $element->grid_id = $grid->id;
                $element->elementable_type = $model;
                $element->elementable_id = $block->type_id;
                $element->options = json_encode($options);
                $element->save();
            }

            $content = json_encode(
                [
                'id' => $grid->id
                ]
            );
        } elseif ($request->module == 'image') {
            $file = $request->file('file');
            $media = Utility::save_image($file);

            $content = json_decode($request->data);
            $content->src = $media->landscape;
            $content = json_encode($content);
        }



        $module->type = $request->module;
        $module->modulable_type = $request->model;
        $module->modulable_id = $request->model_id;
        $module->content = $content;
        $module->save();

        return $module;
    }
}
