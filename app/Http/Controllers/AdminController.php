<?php

namespace App\Http\Controllers;

use App\Pdf;
use App\Post;
use App\News;
use App\Grid;
use App\Slug;
use App\Module;
use App\Product;
use App\Element;
use App\Utility;
use App\SubPage;
use App\Sidebar;
use App\Category;
use App\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function test()
    {
        // $request = new Request();
        // $request->replace(
        //     [
        //         "id" => "10",
        //         // "type" => "title",
        //         // "modulable_type" => "App\\StaticPage",
        //         // "modulable_id" => "2",
        //         // "content" => "{\"content\":\"Giuseppe\",\"fontSize\":\"h2\",\"color\":null,\"isColumn\":null,\"uppercase\":null}",
        //         // "created_at" => "2019-06-06 00:35:30",
        //         // "updated_at" => "2019-06-06 00:35:30",
        //         // "uuid" => "5qngii0q09",
        //         // "isNew" => "false"
        //     ]
        // );
        // return $this->save_component($request);
        return $this->get_page(4);
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

        case 'pages':
            $pages = StaticPage::with('slug')->get();
            $sub_pages = SubPage::with('slug')->get();
            $elements = $this->uniform_and_merge($pages, $sub_pages);
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

    public function get_posts_type($type)
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

    public function get_post_type($type, $id)
    {
        $model = 'App\\'.ucfirst($type);
        $post = $model::with('slug', 'category', 'modules', 'sidebar.modules')->where('id', $id)->first();

        if ($post->modules) {
            $post->modules = Utility::format_complex_modules($post->modules, false);
        }

        if ($post->sidebar && $post->sidebar->modules) {
            $post->sidebar->modules = Utility::format_complex_modules($post->sidebar->modules, false);

        }

        return [
            'success' => true,
            'post' => $post,
        ];
    }

    public function save_post_type(Request $request)
    {
        $model = 'App\\'.ucfirst($request->model);

        $post = isset($request->id) ? $model::find($request->id) : new $model();

        $post->category_id = $request->category;
        if (isset($request->title)) {
            $post->title = $request->title;
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $media = Utility::save_image($file);
            $post->thumb = $media->landscape;
        } else {
            $post->thumb = 'no-image';
        }

        if (isset($request->price)) {
            $post->price = $request->price;
        }

        if (isset($request->start_at) && $request->start_at && $request->start_at != 'null') {
            $string_start = strtotime($request->start_at);

            $start_at = Carbon::parse($string_start)->format('Y-m-d H:i:s');
            $post->start_at = $start_at;
        }

        if (isset($request->end_at) && $request->end_at && $request->end_at != 'null') {
            $string_end = strtotime($request->end_at);
            $end_at = Carbon::parse($string_end)->format('Y-m-d H:i:s');
            $post->end_at = $end_at;
        }

        if (isset($request->hours)) {
            $post->hours = $request->hours;
        }

        if (isset($request->address)) {
            $post->address = $request->address;
        }

        if (isset($request->options)) {
            $post->options = $request->options;
        }

        if (isset($request->forwho)) {
            $post->forwho = $request->forwho;
        }

        $post->save();

        // salva slug
        if (isset($request->id)) {
            $slug = $post->slug()->first();
            if (!$slug) {
                $slug = new Slug();
            }
        } else {
            $slug = new Slug();
        }

        $slug->sluggable_type = $model;
        $slug->sluggable_id = $post->id;
        $slug->slug = $request->slug;
        $slug->save();

        $post->slug = $post->slug;

        return [
            'success' => true,
            'post' => $post,
            // 'debug' => [
            //     'start_at' => $start_at,
            //     'string' => $string_start,
            //     'end_at' => $end_at,
            //     'string_end' => $string_end,
            // ],
        ];
    }

    public function get_categories()
    {
        $categories = Category::all();
        return [
            'success' => true,
            'categories' => $categories
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
        // return [$request->all()];
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

        if ($module->type === 'grid') {
            $content = json_decode($module->content);
            $grid_id = $content->id;
            $grid = Grid::find($grid_id);
            $grid->elements()->delete();
        }

        $module->delete();

        return [
            'success' => true,
            'module' => $module,
        ];
    }

    public function get_sub_page($id)
    {
        $page = SubPage::with('sidebar.modules')->where('id', $id)->first();
        if ($page->sidebar && $page->sidebar->modules) {
            $page->sidebar->modules = Utility::format_complex_modules($page->sidebar->modules, false);
        }
        $page->modules = Utility::format_complex_modules($page->modules()->get(), false);
        return $page;
    }

    public function get_page($id)
    {
        $page = StaticPage::with('sidebar.modules')->where('id', $id)->first();
        if ($page->sidebar && $page->sidebar->modules) {
            $page->sidebar->modules = Utility::format_complex_modules($page->sidebar->modules, false);
        }
        $page->modules = Utility::format_complex_modules($page->modules()->get(), false);
        return $page;
    }

    public function create_sidebar(Request $request)
    {
        $sidebar = Sidebar::where(
            [
            ['sidebarable_id', '=', $request->sidebarable_id],
            ['sidebarable_type', '=', $request->sidebarable_type]
            ]
        )->first();

        if (!$sidebar) {
            $sidebar = new Sidebar();
            $sidebar->sidebarable_id = $request->sidebarable_id;
            $sidebar->sidebarable_type = $request->sidebarable_type;
            $sidebar->save();
        }

        return [
            'success' => true,
            'sidebar' => $sidebar
        ];
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
            $grid_options = json_decode($content->options);
            $grid_content = json_decode($module->content);

            if (isset($grid_content->id)) {
                $grid_id = $grid_content->id;
                $grid = Grid::find($grid_id);
                if (!$grid) {
                    $grid = new Grid();
                } else {
                    $grid->elements()->delete();
                }
            } else {
                $grid = new Grid();
            }


            // $grid->title = $content->title;
            $grid->type = $content->type;
            $grid->save();



            foreach ($content->blocks as $key => $block) {
                $model = 'App\\'.ucfirst($block->type);

                $options = [
                    'x' => $block->x,
                    'y' => $block->y,
                    'order' => $block->order,
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
                }
                $element = new Element();

                $element->grid_id = $grid->id;
                $element->elementable_type = $model;
                $element->elementable_id = $block->type_id;
                $element->options = json_encode($options);
                $element->save();
            }

            $content = json_encode(
                [
                    'id' => $grid->id,
                    'options' => $grid_options,
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

    public function save_image(Request $request)
    {
        $file = $request->file('file');
        $media = Utility::save_image($file);

        return [
            'file' => $media
        ];
    }

    public function upload_pdf(Request $request)
    {
        $file = $request->file('pdf');

        $original_filename = $file->getClientOriginalName();
        $filename = uniqid().'.'.$file->getClientOriginalExtension();

        $src = $file->storeAs('public/pdf', $filename);
        $src_formatted = Storage::disk('local')->url($src);

        $pdf = new Pdf();
        $pdf->original_name = $original_filename;
        $pdf->src = $src_formatted;
        $pdf->save();

        return [
            'success' => true,
            'pdf' => $pdf
        ];
    }
}
