<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sidebar extends Model
{
    protected $table = 'sidebars';

    public function sidebarable() {
        return $this->morphTo();
    }

    public function modules() {
        return $this->morphMany(Module::class, 'modulable');
    }
}
