<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $table = 'subscribers';

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
