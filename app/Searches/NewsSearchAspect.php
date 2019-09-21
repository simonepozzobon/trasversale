<?php

namespace App\Searches;

use App\News;
use Spatie\Searchable\SearchAspect;
use Illuminate\Support\Collection;

class NewsSearchAspect extends SearchAspect
{
    public static $searchType = 'News';

    public function getResults(string $term): Collection
    {
        return News::query()
            ->where('title', 'LIKE', "%{$term}%")
            ->orWhere('author', 'LIKE', "%{$term}%")
            ->orWhereHas('category', function($query) use ($term) {
                $query->where('title', 'LIKE', "%{$term}%");
            })
            ->orWhereHas('modules', function($query) use ($term) {
                $query->where('content', 'LIKE', "%{$term}%");
            })
            ->get();
    }
}
