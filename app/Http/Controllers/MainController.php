<?php

namespace App\Http\Controllers;

use App\Slug;
use App\SubPage;
use App\Utility;
use App\StaticPage;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home() {
        return view('welcome');
    }

    public function get_static_page($static_page) {
        $slug = Slug::where('slug', $static_page)->with('sluggable')->first();
        $page = $slug->sluggable;
        return view('static-page', compact('page'));
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

}
