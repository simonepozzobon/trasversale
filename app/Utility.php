<?php

namespace App;

use App\SubPage;
use App\StaticPage;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Utility extends Model
{
    public static function generate_menu() {
        $pages = StaticPage::all();

        $pages = $pages->transform(function($page, $key) {
            $page->slug = $page->slug->slug;

            $sub_pages = $page->sub_pages;
            $page->sub_pages = $sub_pages->transform(function($sub, $key) {
                $sub->slug = $sub->slug->slug;
                return $sub;
            });

            return $page;
        });

        return $pages;
    }


    public static function save_image($file) {
        $filename = uniqid().'.'.$file->getClientOriginalExtension();
        $src = $file->storeAs('public/media', $filename);

        // Pre-save files to be resized
        $thumbnail = $file->storeAs('public/media', 'thumb_'.$filename);
        $thumbnail_md = $file->storeAs('public/media', 'thumb_md_'.$filename);
        $landscape = $file->storeAs('public/media', 'landscape_'.$filename);

        // Path where files are stored according to Filesystem.php
        $path = storage_path('app/public/media');

        // Edit files
        $img_thumbnail = Image::make($path.'/thumb_'.$filename)->fit(150)->save();
        $img_thumbnail_md = Image::make($path.'/thumb_md_'.$filename)->fit(400)->save();
        $img_landscape = Image::make($path.'/landscape_'.$filename)->resize( 1920, null, function ($constraint) {
            $constraint->aspectRatio(); $constraint->upsize();
        })->save();

        $media = new Media;
        $media->src = Storage::disk('local')->url($src);
        $media->thumb = Storage::disk('local')->url($thumbnail);
        $media->thumb_md = Storage::disk('local')->url($thumbnail_md);
        $media->landscape = Storage::disk('local')->url($landscape);
        $media->save();

        return $media;
    }
}
