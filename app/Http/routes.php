<?php

Route::get('/', function () {
    return view('welcome');
});

Route::any('/test', 'qyWechatController@test');
Route::any('/temp', 'TestController@test');
Route::any('/jianpiao', 'JianPiao\JianPiaoController@index');
//年卡查询
Route::any('/cardquery', 'Card\CardQueryController@index');

//年卡办理
Route::any('/cardcreate', 'Card\CardCreateController@index');


Route::any('/message', 'Message\MessageController@index');
Route::any('/tglm', 'Tglm\TglmController@index');

Route::get('token','TokenController@get');

//自动推送成功订单到推广联盟组
Route::post('/sendmessage/tglm','Tglm\TglmController@sendmessage');