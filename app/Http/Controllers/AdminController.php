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
        $request = new Request();
        $request->replace([
            "id" => "80",
            "model_id" => "4",
            "model" => "App\\StaticPage",
            "module" => "grid",
            "data" => "{\"title\":\"corsi\",\"type\":\"simple\",\"elements\":{\"model\":\"last-mix\",\"post_count\":10,\"blocks\":[{\"i\":0,\"x\":0,\"y\":0,\"w\":4,\"h\":2,\"order\":0,\"id\":16,\"type\":\"product\",\"type_id\":1,\"width\":4,\"height\":2,\"bgColor\":\"secondary\",\"thumb\":\"\/dummies\/main-grid\/dummy_3.jpg\",\"created_at\":null,\"content\":\"{\\\"id\\\":1,\\\"category_id\\\":4,\\\"title\\\":\\\"Coaching Per Gli Insegnanti\\\",\\\"price\\\":350,\\\"thumb\\\":\\\"\\\\\/dummies\\\\\/main-grid\\\\\/dummy_3.jpg\\\",\\\"options\\\":\\\"\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"slug\\\":{\\\"id\\\":22,\\\"slug\\\":\\\"coaching-per-gli-insegnanti\\\",\\\"sluggable_type\\\":\\\"App\\\\\\\\Product\\\",\\\"sluggable_id\\\":1,\\\"created_at\\\":null,\\\"updated_at\\\":null},\\\"category\\\":{\\\"id\\\":4,\\\"title\\\":\\\"Coaching\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null}}\",\"moved\":false},{\"i\":1,\"x\":4,\"y\":0,\"w\":4,\"h\":2,\"order\":1,\"id\":17,\"type\":\"product\",\"type_id\":2,\"width\":4,\"height\":2,\"bgColor\":\"secondary\",\"thumb\":\"\/dummies\/main-grid\/dummy_13.jpg\",\"created_at\":null,\"content\":\"{\\\"id\\\":2,\\\"category_id\\\":4,\\\"title\\\":\\\"Coaching Per Genitori\\\",\\\"price\\\":350,\\\"thumb\\\":\\\"\\\\\/dummies\\\\\/main-grid\\\\\/dummy_13.jpg\\\",\\\"options\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"slug\\\":{\\\"id\\\":23,\\\"slug\\\":\\\"coaching-per-genitori\\\",\\\"sluggable_type\\\":\\\"App\\\\\\\\Product\\\",\\\"sluggable_id\\\":2,\\\"created_at\\\":null,\\\"updated_at\\\":null},\\\"category\\\":{\\\"id\\\":4,\\\"title\\\":\\\"Coaching\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null}}\",\"moved\":false},{\"i\":2,\"x\":8,\"y\":0,\"w\":4,\"h\":2,\"order\":2,\"id\":18,\"type\":\"product\",\"type_id\":3,\"width\":4,\"height\":2,\"bgColor\":\"secondary\",\"thumb\":\"\/dummies\/main-grid\/dummy_14.jpg\",\"created_at\":null,\"content\":\"{\\\"id\\\":3,\\\"category_id\\\":9,\\\"title\\\":\\\"Tutoring\\\",\\\"price\\\":350,\\\"thumb\\\":\\\"\\\\\/dummies\\\\\/main-grid\\\\\/dummy_14.jpg\\\",\\\"options\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"slug\\\":{\\\"id\\\":24,\\\"slug\\\":\\\"tutoring\\\",\\\"sluggable_type\\\":\\\"App\\\\\\\\Product\\\",\\\"sluggable_id\\\":3,\\\"created_at\\\":null,\\\"updated_at\\\":null},\\\"category\\\":{\\\"id\\\":9,\\\"title\\\":\\\"Tutoring\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null}}\",\"moved\":false},{\"i\":3,\"x\":0,\"y\":2,\"w\":4,\"h\":2,\"order\":3,\"id\":19,\"type\":\"product\",\"type_id\":4,\"width\":4,\"height\":2,\"bgColor\":\"secondary\",\"thumb\":\"\/dummies\/main-grid\/dummy_3.jpg\",\"created_at\":null,\"content\":\"{\\\"id\\\":4,\\\"category_id\\\":5,\\\"title\\\":\\\"Corso Settore Agricolo\\\",\\\"price\\\":350,\\\"thumb\\\":\\\"\\\\\/dummies\\\\\/main-grid\\\\\/dummy_3.jpg\\\",\\\"options\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"slug\\\":{\\\"id\\\":26,\\\"slug\\\":\\\"coaching-per-gli-insegnanti-2\\\",\\\"sluggable_type\\\":\\\"App\\\\\\\\Product\\\",\\\"sluggable_id\\\":4,\\\"created_at\\\":null,\\\"updated_at\\\":null},\\\"category\\\":{\\\"id\\\":5,\\\"title\\\":\\\"Settore Agricolo\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null}}\",\"moved\":false},{\"i\":4,\"x\":4,\"y\":2,\"w\":4,\"h\":2,\"order\":4,\"id\":20,\"type\":\"product\",\"type_id\":5,\"width\":4,\"height\":2,\"bgColor\":\"secondary\",\"thumb\":\"\/dummies\/main-grid\/dummy_13.jpg\",\"created_at\":null,\"content\":\"{\\\"id\\\":5,\\\"category_id\\\":6,\\\"title\\\":\\\"Corso di formazione finanziaria\\\",\\\"price\\\":350,\\\"thumb\\\":\\\"\\\\\/dummies\\\\\/main-grid\\\\\/dummy_13.jpg\\\",\\\"options\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"slug\\\":{\\\"id\\\":28,\\\"slug\\\":\\\"coaching-per-genitori-2\\\",\\\"sluggable_type\\\":\\\"App\\\\\\\\Product\\\",\\\"sluggable_id\\\":5,\\\"created_at\\\":null,\\\"updated_at\\\":null},\\\"category\\\":{\\\"id\\\":6,\\\"title\\\":\\\"Formazione Finanziaria\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null}}\",\"moved\":false},{\"i\":5,\"x\":8,\"y\":2,\"w\":4,\"h\":2,\"order\":5,\"id\":21,\"type\":\"product\",\"type_id\":6,\"width\":4,\"height\":2,\"bgColor\":\"secondary\",\"thumb\":\"\/dummies\/main-grid\/dummy_3.jpg\",\"created_at\":null,\"content\":\"{\\\"id\\\":6,\\\"category_id\\\":5,\\\"title\\\":\\\"Corso Settore Agricolo 2\\\",\\\"price\\\":350,\\\"thumb\\\":\\\"\\\\\/dummies\\\\\/main-grid\\\\\/dummy_3.jpg\\\",\\\"options\\\":null,\\\"created_at\\\":null,\\\"updated_at\\\":null,\\\"slug\\\":{\\\"id\\\":27,\\\"slug\\\":\\\"coaching-per-genitori-3\\\",\\\"sluggable_type\\\":\\\"App\\\\\\\\Product\\\",\\\"sluggable_id\\\":6,\\\"created_at\\\":null,\\\"updated_at\\\":null},\\\"category\\\":{\\\"id\\\":5,\\\"title\\\":\\\"Settore Agricolo\\\",\\\"created_at\\\":null,\\\"updated_at\\\":null}}\",\"moved\":false}]}}"
        ]);
        $module = Module::find($request->id);
        $module = $this->set_module($module, $request);
        dd($module);

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

    public function get_post_type($type) {
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

    public function save_component(Request $request) {

        if ($request->id == 'null') {
            $module = new Module();
        } else {
            $module = Module::find($request->id);
        }
        $module = $this->set_module($module, $request);

        return [
            'success' => true,
            'module' => $module,
        ];
    }
    //
    // public function update_component(Request $request) {
    //
    //     $module = Module::find($request->id);
    //     $module = $this->set_module($module, $request);
    //
    //     return [
    //         'success' => true,
    //         'module' => $module,
    //     ];
    // }

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

    public function get_page($id) {
        $page = StaticPage::find($id);
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
                    'thumb' => $block->thumb,
                ];

                // dd($block);
                $hasElement = isset($block->id);
                if ($hasElement) {
                    $element = Element::find($block->id);
                } else {
                    $element = new Element();
                }
                $element->grid_id = $grid->id;
                $element->elementable_type = $model;
                $element->elementable_id = $block->type_id;
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
