<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

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
}
