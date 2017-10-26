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

Route::get('films', 'FilmsController@index');
Route::get('settings', 'SettingsController@index');
Route::put('subscription', 'SubscriptionController@subscribe');
Route::delete('subscription', 'SubscriptionController@unSubscribe');
Route::get('films/{hash}', 'FilmsController@show');

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
