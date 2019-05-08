<?php

namespace App\Http\Controllers;

use App\Utility;
use App\SubPage;
use App\Module;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index($slug = null) {
        $pages = Utility::generate_menu();
        return view('admin.index', compact('pages'));
    }

    public function save_component(Request $request) {
        
        return [
            'success' => true,
        ];
    }

    public function get_sub_page($id) {
        $page = SubPage::find($id);
        $page->modules = $page->modules()->get();
        return $page;
    }
}
