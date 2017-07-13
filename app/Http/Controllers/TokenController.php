<?php

namespace App\Http\Controllers;


use Stoneworld\Wechat\Server;

use Illuminate\Http\Request;

use App\Http\Requests;

class TokenController extends Controller
{


    public function get()
    {
        $options = array(
            'token'=>'yz43hRyIDGFUdQy3qtaZ0',	//填写应用接口的Token
            'encodingaeskey'=>'Eu6T9BjvcKn3m2s2DR87uCAE1M4tnbrqXdJ6nTb7DMh',//填写加密用的EncodingAESKey
            'appid'=>'wx6bb8b192d1dcfe19',	//填写高级调用功能的appid
            'appsecret'=>'H4AyKWaEQiUJg7fQ5abwqrTF_QbjlnzJ8AZlPC_Ll9CiAwote4G-mXOE6C9YDNtj',
            'debug'=>false,
            'logcallback'=>'logg',
            'agentid' => '8', //应用的id

        );
//        logg("GET参数为：\n".var_export($_GET,true));
        $weObj = new \Wechat($options);
        return $weObj->checkAuth();
    }
}
