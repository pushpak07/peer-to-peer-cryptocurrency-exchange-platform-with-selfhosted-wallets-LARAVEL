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

// Offers
Route::group(['namespace' => 'Resources', 'middleware' => 'authorize.public_ip'], function () {
    // Offers
    Route::group(['prefix' => 'offers'], function (){
        // Buy
        Route::get('buy', 'OffersController@buy')->name('buy');

        // Sell
        Route::get('sell', 'OffersController@sell')->name('sell');

        // Get
        Route::get('{id}', 'OffersController@get')->name('get');
    });
});

