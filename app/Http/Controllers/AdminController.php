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
    public function test() {
        $this->get_grid_elements('products');
    }

    public function index($slug = null) {
        $pages = Utility::generate_menu();
        return view('admin.index', compact('pages'));
    }

    public function get_grid_elements($type) {
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

    public function save_component(Request $request) {

        if ($request->id == 'null') {
            $module = new Module();
        } else {
            $module = Module::find($request->id);
        }
        $module = $this->set_module($module, $request);

        return [
            'success' => true,
            // 'module' => $module,
            'debug' => $module,
        ];
    }

    public function update_component(Request $request) {

        $module = Module::find($request->id);
        $module = $this->set_module($module, $request);

        return [
            'success' => true,
            'module' => $module,
        ];
    }

    public function delete_component($id) {
        $module = Module::find($id);
        $module->delete();

        return [
            'success' => true,
            'module' => $module,
        ];
    }

    public function get_sub_page($id) {
        $page = SubPage::find($id);
        $page->modules = Utility::format_complex_modules($page->modules()->get());

        return $page;
    }

    public function uniform_and_merge() {
        $collections = func_get_args();
        $merged = collect();

        foreach ($collections as $key => $collection) {
            $uniformed = $this->uniform_models($collection)->all();
            $merged = $merged->concat($uniformed);
        }

        $sorted = $merged->sortBy('updated_at')->values();
        return $sorted;
    }

    public function uniform_models($collection) {
        $formatted = $collection->transform(function($item, $key) {
            $model = strtolower(str_replace('App\\', '', get_class($item)));
            $item->type = $model;

            // debug
            $item->selected = 0;
            return $item;
        });
        return $collection;
    }

    public function set_module($module, $request) {
        $content = $request->data;

        if ($request->module == 'grid') {
            $content = json_decode($request->data);

            $grid = new Grid();
            $grid->title = $content->title;
            $grid->type = $content->type;
            $grid->save();

            foreach ($content->elements->items as $key => $item) {
                $model = 'App\\'.ucfirst($item->type);
                $options = [
                    'width' => $item->w,
                    'height' => $item->h,
                    'color' => 'primary',
                    'content' => 'title',
                    'img' => $item->thumb,
                ];

                $element = new Element();
                $element->grid_id = $grid->id;
                $element->elementable_type = $model;
                $element->elementable_id = $item->id;
                $element->options = json_encode($options);
                $element->save();
            }

            $content = json_encode([
                'id' => $grid->id
            ]);
        }

        else if ($request->module == 'image' && $request->hasFile('file')) {
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
