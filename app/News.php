<?php

namespace App;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\Model;

class News extends Model implements Searchable
{
    protected $table = 'news';

    public function getSearchResult(): SearchResult {

        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->title
        );
    }

    public function elements() {
        return $this->morphMany(Element::class, 'elementable');
    }

    public function slug() {
        return $this->morphOne(Slug::class, 'sluggable');
    }

    public function modules() {
        return $this->morphMany(Module::class, 'modulable');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function sidebar() {
        return $this->morphOne(Sidebar::class, 'sidebarable');
    }
}
