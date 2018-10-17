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

Route::middleware('api')->post('/panels', 'PanelController@store');

Route::middleware('api')->get('/one_hour_electricities', 'OneHourElectricityController@index');

Route::middleware('api')->post('/one_hour_electricities', 'OneHourElectricityController@store');

Route::middleware('api')->get('/one_day_electricities', 'OneDayElectricityController@index');
