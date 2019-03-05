<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubPage extends Model
{
    protected $table = 'sub_pages';

    public function page() {
        return $this->belongsTo(StaticPage::class, 'static_page_id', 'id');
    }

    public function modules() {
        return $this->morphMany(Module::class, 'modulable');
    }

    public function slug() {
        return $this->morphOne(Slug::class, 'sluggable');
    }
}
