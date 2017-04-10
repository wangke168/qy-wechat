<?php

Route::get('/', function () {
    return view('welcome');
});

Route::any('/test', 'qyWechatController@test');

Route::any('/jianpiao', 'JianPiao\JianPiaoController@index');

Route::any('/tglm', 'Tglm\TglmController@index');

Route::get('token','TokenController@get');

//自动推送成功订单到推广联盟组
//Route::get('/sendmessage/tglm/{sellid}','Tglm\TglmController@sendmessage');