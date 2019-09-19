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

    public function sidebar() {
        return $this->morphOne(Sidebar::class, 'sidebarable');
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
