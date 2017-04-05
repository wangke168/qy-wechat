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

Route::any('/test', 'qyWechatController@test');

Route::any('/jianpiao', 'JianPiao\JianPiaoController@index');

Route::any('/tglm', 'qyWechatController@tglm');

Route::get('token','TokenController@get');