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
                $item->sluggable->modules = Utility::format_complex_modules($item->sluggable->modules);
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

    public function stringify_class($item) {
        $string = get_class($item);
        $string = str_replace('App\\', '', $string);
        $string = strtolower($string);
        return $string;
    }

}
