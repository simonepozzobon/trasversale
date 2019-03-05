<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slug extends Model
{
    protected $table = 'slugs';

    protected $fillable = ['slug'];

    public function sluggable() {
        return $this->morphTo();
    }
}
