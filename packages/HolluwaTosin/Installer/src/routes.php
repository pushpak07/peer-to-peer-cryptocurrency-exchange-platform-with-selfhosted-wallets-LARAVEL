<?php
/**
 * ======================================================================================================
 * File Name: routes.php
 * ======================================================================================================
 * Author: HolluwaTosin360
 * ------------------------------------------------------------------------------------------------------
 * Portfolio: http://codecanyon.net/user/holluwatosin360
 * ------------------------------------------------------------------------------------------------------
 * Date & Time: 10/21/2018 (5:20 AM)
 * ------------------------------------------------------------------------------------------------------
 *
 * Copyright (c) 2018. This project is released under the standard of CodeCanyon License.
 * You may NOT modify/redistribute this copy of the project. We reserve the right to take legal actions
 * if any part of the license is violated. Learn more: https://codecanyon.net/licenses/standard.
 *
 * ------------------------------------------------------------------------------------------------------
 */

Route::group([
    'prefix' => 'installer', 'as' => 'Installer::', 'namespace' => 'HolluwaTosin\Installer\Controllers', 'middleware' => ['web']
], function () {

    // Install
    Route::group(['prefix' => 'overview', 'as' => 'overview.', 'middleware' => ['installer.can_install']], function () {
        Route::get('', 'InstallController@index')->name('index');
        Route::post('', 'InstallController@verify');

        Route::middleware('installer.validate_session')->group(function () {
            Route::get('requirements', 'InstallController@requirements')->name('requirements');

            Route::get('permissions', 'InstallController@permissions')->name('permissions');

            Route::get('environment', 'InstallController@environment')->name('environment');
            Route::post('environment', 'InstallController@saveEnvironment');
        });

        Route::get('finish', 'InstallController@finish')->name('finish');
        Route::post('finish', 'InstallController@start');
    });

    // Update
    Route::group(['prefix' => 'update', 'as' => 'update.'], function () {
        Route::middleware(['installer.can_update'])->group(function (){
            Route::get('', 'UpdateController@index')->name('index');
            Route::post('', 'UpdateController@update');
        });

        Route::get('finish', 'UpdateController@finish')->name('finish');
    });

    // Verify
    Route::group(['prefix' => 'verify', 'as' => 'verify.', 'middleware' => ['installer.can_verify']], function () {
        Route::get('', 'VerifyController@index')->name('index');
        Route::post('', 'VerifyController@verify');
    });

});
