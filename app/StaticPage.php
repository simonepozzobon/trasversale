<?php

namespace App;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model implements Searchable
{
    public function getSearchResult(): SearchResult {

        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->title
        );
    }

    public function sub_pages() {
        return $this->hasMany(SubPage::class);
    }

    public function slug() {
        return $this->morphOne(Slug::class, 'sluggable');
    }

    public function modules() {
        return $this->morphMany(Module::class, 'modulable');
    }

    public function sidebar() {
        return $this->morphOne(Sidebar::class, 'sidebarable');
    }
}
