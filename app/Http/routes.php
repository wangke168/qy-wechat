<?php

Route::get('/', function () {
    return view('welcome');
});

Route::any('/test', 'qyWechatController@test');

Route::any('/jianpiao', 'JianPiao\JianPiaoController@index');

Route::any('/tglm', 'Tglm\TglmController@index');


Route::get('token','TokenController@get');