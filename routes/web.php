<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('test', 'MainController@test');
// Route::get('test', 'AdminController@test');

Route::prefix('admin')->group(function() {
    Route::get('/', 'AdminController@index');
    Route::get('/{slug}', 'AdminController@index')->where('slug', '.*');
});

Route::get('/{slug}', 'MainController@home')->where('slug', '.*')->name('home');

//
// try {
//     $pages = App\StaticPage::all();
//     foreach ($pages as $key => $page) {
//         Route::get('/{static_page}', 'MainController@get_static_page')->name('static.page');
//         Route::get($page->slug->slug.'/{slug}', 'MainController@get_sub_page')->name('sub-page');
//     }
// } catch (\Exception $e) {
//
// }
