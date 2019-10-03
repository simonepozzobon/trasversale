<?php

namespace App\Http\Controllers;

use App\Slug;
use App\Grid;
use App\Module;
use App\SubPage;
use App\Utility;
use App\StaticPage;
use App\Product;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home()
    {
        return view('welcome');
    }

    public function get_products()
    {
        $products = Product::with('slug')->get();
        return [
            'products' => $products
        ];
    }

    public function test()
    {
        $test = $this->get_dynamic_item('home', null, null);
        dd($test);
    }

    public function get_dynamic_item($page = null, $subpage = null, $slug = null)
    {
        if ($slug) {
            $item = Slug::where(
                [
                    ['slug', '=', $slug],
                    ['sluggable_type', '!=', 'App\\StaticPage'],
                    ['sluggable_type', '!=', 'App\\SubPage'],
                ]
            )->with('sluggable.modules', 'sluggable.sidebar.modules')->first();
        }

        else if ($subpage) {
            $item = Slug::where(
                [
                    ['slug', '=', $subpage],
                    ['sluggable_type', '=', 'App\\SubPage'],
                ]
            )->orWhere(
                [
                    ['slug', '=', $subpage],
                    ['sluggable_type', '=', 'App\\News'],
                ]
            )->orWhere(
                [
                    ['slug', '=', $subpage],
                    ['sluggable_type', '=', 'App\\Post'],
                ]
            )->orWhere(
                [
                    ['slug', '=', $subpage],
                    ['sluggable_type', '=', 'App\\Product'],
                ]
            )->with('sluggable.modules', 'sluggable.sidebar.modules')->first();
        }

        else {
            $item = Slug::where(
                [
                    ['slug', '=', $page],
                    ['sluggable_type', '=', 'App\\StaticPage'],
                ]
            )->with('sluggable.modules', 'sluggable.sidebar.modules')->first();
        }

        if ($item) {
            if ($item->sluggable->modules->count() > 0) {
                $modules = $item->sluggable->modules->sortBy('order')->values();
                $item->sluggable->modules = Utility::format_complex_modules($modules, true);

                $sidebar = $item->sluggable->sidebar;
                if ($sidebar) {
                    $sidebar->modules = $sidebar->modules;
                } else {
                    $sidebar = collect();
                }
                $item->sluggable->sidebar = $sidebar;
            }

            $item->sluggable->model = $this->stringify_class($item->sluggable);

            return [
                'success' => true,
                // 'debug' => $this->stringify_class($item->sluggable),
                'item' => $item->sluggable,
            ];
        }

        return [
            'success' => false,
        ];
    }

    public function get_static_page($static_page)
    {
        $slug = Slug::where('slug', $static_page)->with('sluggable')->first();
        $page = $slug->sluggable;
        return $page;
    }

    public function get_sub_page($sub_page)
    {

        $slug = Slug::where('slug', $sub_page)->with('sluggable')->first();
        $sub = $slug->sluggable()->with('page.slug', 'modules')->first();
        $sub->page = $sub->page;
        $sub->modules = $sub->modules;
        return view('sub-page', compact('sub'));

    }

    public function dummy_page()
    {
        $pages = Collect();
        return true;
    }

    public function stringify_class($item)
    {
        $string = get_class($item);
        $string = str_replace('App\\', '', $string);
        $string = strtolower($string);
        return $string;
    }

}
