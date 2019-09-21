<?php

namespace App\Http\Controllers;

use App\News;
use App\Post;
use App\Product;
use App\StaticPage;
use App\SubPage;

use App\Searches\NewsSearchAspect;
use App\Searches\SubPageSearchAspect;
use App\Searches\ProductSearchAspect;
use App\Searches\StaticPageSearchAspect;

use Spatie\Searchable\Search;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function test() {
        $request = new Request();
        $request->replace([
            'string' => 'puccini',
        ]);

        $res = $this->simple_search($request);
        dump($res);

    }


    public function simple_search (Request $request)
    {
        $results = (new Search())
            ->registerAspect(NewsSearchAspect::class)
            ->registerAspect(SubPageSearchAspect::class)
            ->registerAspect(ProductSearchAspect::class)
            ->registerAspect(StaticPageSearchAspect::class)
            ->search($request->string);

        $results = $results->transform(function($result, $key) {
            $slug = $result->searchable->slug->slug;

            if ($result->type == 'SubPage') {
                $parent = $result->searchable->page->slug->slug;
                $result->searchable->parent = $parent;
            }

            $result->searchable->slug = $slug;

            return $result;
        });

        return $results;
    }
}
