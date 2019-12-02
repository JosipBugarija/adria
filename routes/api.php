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

Route::group(['namespace' => 'Api'], function () {
    Route::group(['middleware' => 'throttle:500,10'], function () {
        Route::get('/controllers', 'ApiController@controllers');
        Route::get('/holiday-and-air', 'ApiController@holidayAndAir');
    });
});
