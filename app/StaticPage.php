<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    public function sub_pages() {
        return $this->hasMany(SubPage::class);
    }

    public function slug() {
        return $this->morphOne(Slug::class, 'sluggable');
    }

    public function modules() {
        return $this->morphMany(Module::class, 'modulable');
    }
}
