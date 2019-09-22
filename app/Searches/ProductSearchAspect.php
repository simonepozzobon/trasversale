<?php

namespace App\Searches;

use App\Product;
use Spatie\Searchable\SearchAspect;
use Illuminate\Support\Collection;

class ProductSearchAspect extends SearchAspect
{
    public static $searchType = 'Product';

    public function getResults(string $term): Collection
    {
        return Product::query()
            ->where('title', 'LIKE', "%{$term}%")
            ->orWhere('forwho', 'LIKE', "%{$term}%")
            ->orWhere('price', 'LIKE', "%{$term}%")
            ->orWhere('address', 'LIKE', "%{$term}%")
            ->orWhereHas('category', function($query) use ($term) {
                $query->where('title', 'LIKE', "%{$term}%");
            })
            ->orWhereHas('modules', function($query) use ($term) {
                $query->where('content', 'LIKE', "%{$term}%");
            })
            ->get();
    }
}
