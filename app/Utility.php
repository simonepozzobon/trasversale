<?php

namespace App;

use App\SubPage;
use App\StaticPage;
use Illuminate\Database\Eloquent\Model;

class Utility extends Model
{
    public static function generate_menu() {
        $pages = StaticPage::all();

        $pages = $pages->transform(function($page, $key) {
            $page->slug = $page->slug->slug;

            $sub_pages = $page->sub_pages;
            $page->sub_pages = $sub_pages->transform(function($sub, $key) {
                $sub->slug = $sub->slug->slug;
                return $sub;
            });

            return $page;
        });

        return $pages;
    }
}
