<?php

namespace App\Searches;

use App\StaticPage;
use Spatie\Searchable\SearchAspect;
use Illuminate\Support\Collection;

class StaticPageSearchAspect extends SearchAspect
{
    public static $searchType = 'StaticPage';

    public function getResults(string $term): Collection
    {
        return StaticPage::query()
            ->where('title', 'LIKE', "%{$term}%")
            ->orWhereHas('modules', function($query) use ($term) {
                $query->where('content', 'LIKE', "%{$term}%");
            })
            ->get();
    }
}
