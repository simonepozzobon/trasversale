<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'modules';

    protected $fillable = ['type', 'content'];

    public function modulable() {
        return $this->morphTo();
    }
}
