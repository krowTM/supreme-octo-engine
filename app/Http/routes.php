<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('front.home');
});
Route::post('import', 'FrontEndController@import');
Route::post('charts', function () {
    return view('ajax.charts_menu');
});
Route::post('generate-chart', 'FrontEndController@generateChart');