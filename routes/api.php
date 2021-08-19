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

    Route::post('login', 'LoginController@index');
    Route::post('logout', 'LoginController@logout');

    Route::post('admin/search', 'AdminSearchController@index')->middleware('api_auth');

    Route::post('admin/uploads', 'AdminUploadsController@index')->middleware('api_auth');
    Route::post('admin/media', 'AdminUploadsController@media')->middleware('api_auth');
    Route::post('admin/delete-media', 'AdminUploadsController@delete')->middleware('api_auth');

    Route::get('settings', 'SettingsController@index');
    Route::get('options', 'OptionsController@index');

});

