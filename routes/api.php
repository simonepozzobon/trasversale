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

Route::middleware('auth:api')->get(
    '/user', function (Request $request) {
        return $request->user();
    }
);

Route::prefix('payment')->group(function() {
    Route::get('auth', 'PaymentController@generate_token');
    Route::post('order', 'PaymentController@save_order');
    Route::post('transaction', 'PaymentController@create_transaction');
});

Route::prefix('mail')->group(function() {
    Route::post('send', 'MailController@send_mail_form');
    Route::post('cart-info', 'MailController@send_cart_info');
    Route::post('teacher-card', 'MailController@teacher_card');
});

Route::get('get-page/{page}/{subpage?}/{slug?}', 'MainController@get_dynamic_item');

Route::prefix('admin')->group(
    function () {
        Route::post('create-sidebar', 'AdminController@create_sidebar');
        Route::post('save-component', 'AdminController@save_component');
        Route::delete('delete-component/{id}', 'AdminController@delete_component');
        Route::get('grid-elements/{type}', 'AdminController@get_grid_elements');
        Route::get('get-categories', 'AdminController@get_categories');
        // Route::post('update-component', 'AdminController@update_component');

        Route::prefix('post-type')->group(
            function () {
                Route::post('save', 'AdminController@save_post_type');
                Route::get('{type}/{id}', 'AdminController@get_post_type');
                Route::delete('{type}/delete/{id}', 'AdminController@delete_post_type');
                Route::get('{type}', 'AdminController@get_posts_type');
            }
        );

        Route::prefix('sub-page')->group(
            function () {
                Route::get('/{id}', 'AdminController@get_sub_page');
            }
        );

        Route::prefix('page')->group(
            function () {
                Route::get('/{id}', 'AdminController@get_page');
            }
        );

        Route::prefix('utilities')->group(
            function() {
                Route::post('/save-image', 'AdminController@save_image');
            }
        );
    }
);
