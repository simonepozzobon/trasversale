<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    public function elements() {
        return $this->morphMany(Element::class, 'elementable');
    }

    public function modules() {
        return $this->morphMany(Module::class, 'modulable');
    }

    public function slug() {
        return $this->morphOne(Slug::class, 'sluggable');
    }
}
