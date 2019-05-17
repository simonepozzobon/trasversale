<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grid extends Model
{
    protected $table = 'grids';

    public function elements() {
        return $this->hasMany(Element::class);
    }
}
