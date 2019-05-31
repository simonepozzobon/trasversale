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
        $request->replace([
            "id" => "null",
            "model_id" => "2",
            "model" => "App\\StaticPage",
            "module" => "grid",
            "data" => "{\"title\":\"titolo\",\"type\":\"packery\",\"elements\":{\"model\":\"last-mix\",\"post_count\":10,\"blocks\":[{\"i\":0,\"x\":0,\"y\":0,\"w\":2,\"h\":2,\"idx\":0,\"order\":0,\"type_id\":1,\"title\":\"titolo di tre parole dsjkfhksj\",\"created_at\":null,\"updated_at\":null,\"type\":\"post\",\"selected\":true,\"slug\":null},{\"i\":1,\"x\":2,\"y\":0,\"w\":2,\"h\":2,\"idx\":1,\"order\":1,\"type_id\":1,\"category_id\":1,\"title\":\"Modello trento per il sostegno\",\"author\":\"Massimo Rossi\",\"thumb\":\"\/dummies\/main-grid\/dummy_24.jpg\",\"created_at\":null,\"updated_at\":null,\"type\":\"news\",\"selected\":true,\"slug\":{\"id\":29,\"slug\":\"modello-trento\",\"sluggable_type\":\"App\\\\News\",\"sluggable_id\":1,\"created_at\":null,\"updated_at\":null},\"category\":{\"id\":1,\"title\":\"Formazione\",\"created_at\":null,\"updated_at\":null}},{\"i\":2,\"x\":4,\"y\":0,\"w\":2,\"h\":2,\"idx\":2,\"order\":2,\"type_id\":5,\"category_id\":6,\"title\":\"Corso di formazione finanziaria\",\"price\":350,\"thumb\":\"\/dummies\/main-grid\/dummy_13.jpg\",\"options\":null,\"created_at\":null,\"updated_at\":null,\"type\":\"product\",\"selected\":true,\"slug\":{\"id\":28,\"slug\":\"coaching-per-genitori-2\",\"sluggable_type\":\"App\\\\Product\",\"sluggable_id\":5,\"created_at\":null,\"updated_at\":null},\"category\":{\"id\":6,\"title\":\"Formazione Finanziaria\",\"created_at\":null,\"updated_at\":null}},{\"i\":3,\"x\":6,\"y\":0,\"w\":2,\"h\":2,\"idx\":3,\"order\":3,\"type_id\":4,\"category_id\":5,\"title\":\"Corso Settore Agricolo\",\"price\":350,\"thumb\":\"\/dummies\/main-grid\/dummy_3.jpg\",\"options\":null,\"created_at\":null,\"updated_at\":null,\"type\":\"product\",\"selected\":true,\"slug\":{\"id\":26,\"slug\":\"coaching-per-gli-insegnanti-2\",\"sluggable_type\":\"App\\\\Product\",\"sluggable_id\":4,\"created_at\":null,\"updated_at\":null},\"category\":{\"id\":5,\"title\":\"Settore Agricolo\",\"created_at\":null,\"updated_at\":null}},{\"i\":4,\"x\":8,\"y\":0,\"w\":2,\"h\":2,\"idx\":4,\"order\":4,\"type_id\":3,\"category_id\":9,\"title\":\"Tutoring\",\"price\":350,\"thumb\":\"\/dummies\/main-grid\/dummy_14.jpg\",\"options\":null,\"created_at\":null,\"updated_at\":null,\"type\":\"product\",\"selected\":true,\"slug\":{\"id\":24,\"slug\":\"tutoring\",\"sluggable_type\":\"App\\\\Product\",\"sluggable_id\":3,\"created_at\":null,\"updated_at\":null},\"category\":{\"id\":9,\"title\":\"Tutoring\",\"created_at\":null,\"updated_at\":null}},{\"i\":5,\"x\":10,\"y\":0,\"w\":2,\"h\":2,\"idx\":5,\"order\":5,\"type_id\":2,\"category_id\":4,\"title\":\"Coaching Per Genitori\",\"price\":350,\"thumb\":\"\/dummies\/main-grid\/dummy_13.jpg\",\"options\":null,\"created_at\":null,\"updated_at\":null,\"type\":\"product\",\"selected\":true,\"slug\":{\"id\":23,\"slug\":\"coaching-per-genitori\",\"sluggable_type\":\"App\\\\Product\",\"sluggable_id\":2,\"created_at\":null,\"updated_at\":null},\"category\":{\"id\":4,\"title\":\"Coaching\",\"created_at\":null,\"updated_at\":null}},{\"i\":6,\"x\":0,\"y\":1,\"w\":2,\"h\":2,\"idx\":6,\"order\":6,\"type_id\":1,\"category_id\":4,\"title\":\"Coaching Per Gli Insegnanti\",\"price\":350,\"thumb\":\"\/dummies\/main-grid\/dummy_3.jpg\",\"options\":\"\",\"created_at\":null,\"updated_at\":null,\"type\":\"product\",\"selected\":true,\"slug\":{\"id\":22,\"slug\":\"coaching-per-gli-insegnanti\",\"sluggable_type\":\"App\\\\Product\",\"sluggable_id\":1,\"created_at\":null,\"updated_at\":null},\"category\":{\"id\":4,\"title\":\"Coaching\",\"created_at\":null,\"updated_at\":null}},{\"i\":7,\"x\":2,\"y\":1,\"w\":2,\"h\":2,\"idx\":7,\"order\":7,\"type_id\":6,\"category_id\":1,\"title\":\"Intelligenza artificiale con il botto\",\"author\":\"Mario Rossi\",\"thumb\":\"\/dummies\/main-grid\/dummy_17.jpg\",\"created_at\":null,\"updated_at\":null,\"type\":\"news\",\"selected\":true,\"slug\":{\"id\":34,\"slug\":\"intelligenza-artificiale\",\"sluggable_type\":\"App\\\\News\",\"sluggable_id\":6,\"created_at\":null,\"updated_at\":null},\"category\":{\"id\":1,\"title\":\"Formazione\",\"created_at\":null,\"updated_at\":null}},{\"i\":8,\"x\":4,\"y\":1,\"w\":2,\"h\":2,\"idx\":8,\"order\":8,\"type_id\":5,\"category_id\":3,\"title\":\"Reddito di cittadinanza: fetta nemica dell'eficacia\",\"author\":\"Mario Rossi\",\"thumb\":\"\/dummies\/main-grid\/dummy_19.jpg\",\"created_at\":null,\"updated_at\":null,\"type\":\"news\",\"selected\":true,\"slug\":{\"id\":33,\"slug\":\"reddito-cittadinanza-2\",\"sluggable_type\":\"App\\\\News\",\"sluggable_id\":5,\"created_at\":null,\"updated_at\":null},\"category\":{\"id\":3,\"title\":\"Libri\",\"created_at\":null,\"updated_at\":null}},{\"i\":9,\"x\":6,\"y\":1,\"w\":2,\"h\":2,\"idx\":9,\"order\":9,\"type_id\":4,\"category_id\":3,\"title\":\"Modello trento per il sostegno\",\"author\":\"Giovanni Rossi\",\"thumb\":\"\/dummies\/main-grid\/dummy_21.jpg\",\"created_at\":null,\"updated_at\":null,\"type\":\"news\",\"selected\":true,\"slug\":{\"id\":32,\"slug\":\"modello-trento-2\",\"sluggable_type\":\"App\\\\News\",\"sluggable_id\":4,\"created_at\":null,\"updated_at\":null},\"category\":{\"id\":3,\"title\":\"Libri\",\"created_at\":null,\"updated_at\":null}}]}}"
        ]);

        $result = $this->save_component($request);
        dd($result);
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

    public function save_component(Request $request)
    {
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
        $page->modules = Utility::format_complex_modules($page->modules()->get());

        return $page;
    }

    public function get_page($id)
    {
        $page = StaticPage::find($id);
        $page->modules = Utility::format_complex_modules($page->modules()->get());

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
        $formatted = $collection->transform(function ($item, $key) {
            $model = strtolower(str_replace('App\\', '', get_class($item)));
            $item->type = $model;

            // debug
            $item->selected = 0;
            return $item;
        });
        return $collection;
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
                $element = isset($block->id) ? Element::find($block->id) : new Element();

                $element->grid_id = $grid->id;
                $element->elementable_type = $model;
                $element->elementable_id = $block->type_id;
                $element->options = json_encode($options);
                $element->save();
            }

            $content = json_encode([
                'id' => $grid->id
            ]);
        } elseif ($request->module == 'image' && $request->hasFile('file')) {
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
