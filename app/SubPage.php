<?php

namespace App;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\Model;

class SubPage extends Model implements Searchable
{
    protected $table = 'sub_pages';

    public function getSearchResult(): SearchResult {

        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->title
        );
    }

    public function page() {
        return $this->belongsTo(StaticPage::class, 'static_page_id', 'id');
    }

    public function modules() {
        return $this->morphMany(Module::class, 'modulable');
    }

    public function slug() {
        return $this->morphOne(Slug::class, 'sluggable');
    }

    public function sidebar() {
        return $this->morphOne(Sidebar::class, 'sidebarable');
    }
}
