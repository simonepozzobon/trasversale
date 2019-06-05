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
                "uuid" => "0aiqg7ol4g",
                "type" => "row",
                "isNew" => true,
                "modulable_id" => 2,
                "modulable_type" => "App\\StaticPage",
                "content" => "[{\"uuid\":\"1g54uytuzs\",\"type\":\"column\",\"isNew\":true,\"modulable_type\":\"App\\\\Module\",\"content\":{\"size\":6,\"modules\":[{\"uuid\":\"68jg7u601v\",\"type\":\"title\",\"isNew\":true,\"modulable_id\":0,\"modulable_type\":\"App\\\\Module\",\"content\":{\"content\":\"Titollo\",\"fontSize\":\"h2\",\"color\":null,\"isColumn\":true,\"uppercase\":true}}]}},{\"uuid\":\"4u3wf5gja3\",\"type\":\"column\",\"isNew\":true,\"modulable_type\":\"App\\\\Module\",\"content\":{\"size\":6,\"modules\":[]}}]"
            ]
        );
        // return $this->save_component($request);
        return $this->get_page(2);

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

    public function save_component(Request $request)
    {
        if ($request->isNew) {
            $module = new Module();
        } else {
            $module = Module::find($request->id);
        }

        $module = $this->update_module($module, $request);

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

            $content = json_decode($request->data);
            $content->src = $media->landscape;
            $content = json_encode($content);

        } elseif ($request->type == 'row') {
            $columns = json_decode($content);

            // imposto il contenuto del modulo riga
            $content = json_encode(
                [
                    'columns' => count($columns),
                ]
            );

            // salvo la riga
            $module->type = $request->type;
            $module->modulable_type = $request->modulable_type;
            $module->modulable_id = $request->modulable_id;
            $module->content = $content;
            $module->save();

            $row = clone $module;

            // per ogni colonna effettuo il salvataggio
            foreach ($columns as $key => $column) {
                // se è una nuova colonna aggiorno la riga collegata
                $column->modulable_id = $column->isNew ? $row->id : $column->modulable_id;

                // formatto la nuova richiesta
                $column_request = new Request();
                $column_request->replace((array) $column);

                // Ottengo il nuovo(?) modulo colonna
                $column_module = $column->isNew ? new Module() : Module::find($column->id);

                // richiamo la funziona update per il modulo colonna per
                // salvare la colonna appunto
                $column_module = $this->update_module($column_module, $column_request);
            }
        } elseif ($request->type == 'column') {
            $sub_components = isset($content->modules) ? $content->modules : array();

            // rimuovo i moduli dalle opzioni dell colonna
            unset($content->modules);
            $content = json_encode($content);

            // salvo la colonna per poi salvare anche i sotto moduli
            $module->type = $request->type;
            $module->modulable_type = $request->modulable_type;
            $module->modulable_id = $request->modulable_id;
            $module->content = $content;
            $module->save();

            $column = clone $module;

            // salvo ogni sotto modulo
            foreach ($sub_components as $key => $sub_component) {
                // se è un nuovo modulo aggiorno la colonna collegata
                $sub_component->modulable_id = $sub_component->isNew ? $column->id : $sub_component->modulable_id;
                $sub_component->content = json_encode($sub_component->content);

                // formatto la nuova richiesta
                $sub_component_request = new Request();
                $sub_component_request->replace((array) $sub_component);

                // Ottengo il nuovo(?) sotto_componente
                $sub_component_module = $sub_component->isNew ? new Module() : Module::find($sub_component->id);

                // richiamo la funziona update per il sotto_componente
                // così da salvarlo
                $sub_component_module = $this->update_module($sub_component_module, $sub_component_request);
            }
        }

        $module->type = $request->type;
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
