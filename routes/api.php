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

    Route::post('admin/casinos', 'AdminCasinoController@index')->middleware('api_auth');
    Route::post('admin/casino/update', 'AdminCasinoController@update')->middleware('api_auth');
    Route::post('admin/casino/delete', 'AdminCasinoController@delete')->middleware('api_auth');
    Route::post('admin/casino/store', 'AdminCasinoController@store')->middleware('api_auth');
    Route::post('admin/casino/{id}', 'AdminCasinoController@show')->middleware('api_auth');

    Route::post('admin/pokers', 'AdminPokerController@index')->middleware('api_auth');
    Route::post('admin/poker/update', 'AdminPokerController@update')->middleware('api_auth');
    Route::post('admin/poker/delete', 'AdminPokerController@delete')->middleware('api_auth');
    Route::post('admin/poker/store', 'AdminPokerController@store')->middleware('api_auth');
    Route::post('admin/poker/{id}', 'AdminPokerController@show')->middleware('api_auth');

    Route::post('admin/games', 'AdminGameController@index')->middleware('api_auth');
    Route::post('admin/game/update', 'AdminGameController@update')->middleware('api_auth');
    Route::post('admin/game/delete', 'AdminGameController@delete')->middleware('api_auth');
    Route::post('admin/game/store', 'AdminGameController@store')->middleware('api_auth');
    Route::post('admin/game/{id}', 'AdminGameController@show')->middleware('api_auth');

    Route::post('admin/bonuses', 'AdminBonusController@index')->middleware('api_auth');
    Route::post('admin/bonus/update', 'AdminBonusController@update')->middleware('api_auth');
    Route::post('admin/bonus/delete', 'AdminBonusController@delete')->middleware('api_auth');
    Route::post('admin/bonus/store', 'AdminBonusController@store')->middleware('api_auth');
    Route::post('admin/bonus/{id}', 'AdminBonusController@show')->middleware('api_auth');

    Route::get('settings', 'SettingsController@index');
    Route::get('options', 'OptionsController@index');

});

