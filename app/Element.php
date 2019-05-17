<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Element extends Model
{
    protected $table = 'elements';

    public function grid() {
        return $this->belongsTo(Grid::class);
    }

    public function elementable() {
        return $this->morphTo();
    }

    // public function posts() {
    //     return $this->morphedByMany(Post::class, 'elementable');
    // }
    //
    // public function elements() {
    //     //https://stackoverflow.com/questions/35948130/retrieving-all-morphedbymany-relations-in-laravel-eloquent
    //     // return $this->video->union($this->posts)->all();
    //
    //     return $this->posts->all();
    // }
}
