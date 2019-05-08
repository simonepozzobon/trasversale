<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get-page/{slug}', 'MainController@get_dynamic_item');

Route::prefix('admin')->group(function() {
    Route::post('save-component', 'AdminController@save_component');

    Route::prefix('sub-page')->group(function() {
        Route::get('/{id}', 'AdminController@get_sub_page');
    });
});
