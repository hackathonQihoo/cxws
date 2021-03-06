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
    return view('welcome');
});


Route::get('caiyun','CaiyunController@CaiyunGet');

Route::get('caiyun_now','CaiyunController@CaiyunGetNow');

Route::get('locus_set','LocusController@LocusSet');

Route::get('locus_get','LocusController@LocusGet');
