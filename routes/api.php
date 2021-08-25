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

Route::namespace('Api')->group(function () {

    Route::post('admin/login', 'LoginController@index');
    Route::post('admin/logout', 'LoginController@logout');

    Route::post('admin/search', 'AdminSearchController@index')->middleware('api_auth');

    Route::post('admin/uploads', 'AdminUploadsController@index')->middleware('api_auth');
    Route::post('admin/media', 'AdminUploadsController@media')->middleware('api_auth');
    Route::post('admin/delete-media', 'AdminUploadsController@delete')->middleware('api_auth');

    Route::post('admin/casinos', 'AdminCasinoController@index');
    Route::get('admin/casino/update', 'AdminCasinoController@update');
    Route::get('admin/casino/delete', 'AdminCasinoController@delete');
    Route::get('admin/casino/store', 'AdminCasinoController@store');
    Route::post('admin/casino/{id}', 'AdminCasinoController@show');

    Route::get('settings', 'SettingsController@index');
    Route::get('options', 'OptionsController@index');

});

