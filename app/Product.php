<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public function modules() {
        return $this->morphMany(Module::class, 'modulable');
    }

    public function slug() {
        return $this->morphOne(Slug::class, 'sluggable');
    }

    public function elements() {
        return $this->morphMany(Element::class, 'elementable');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}