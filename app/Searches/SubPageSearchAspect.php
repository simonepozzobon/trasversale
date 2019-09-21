<?php

namespace App\Searches;

use App\SubPage;
use Spatie\Searchable\SearchAspect;
use Illuminate\Support\Collection;

class SubPageSearchAspect extends SearchAspect
{
    public static $searchType = 'SubPage';

    public function getResults(string $term): Collection
    {
        return SubPage::query()
            ->where('title', 'LIKE', "%{$term}%")
            ->orWhereHas('modules', function($query) use ($term) {
                $query->where('content', 'LIKE', "%{$term}%");
            })
            ->get();
    }
}
