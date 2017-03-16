<?php

namespace App\Http\Controllers;


use Stoneworld\Wechat\Server;

use Illuminate\Http\Request;

use App\Http\Requests;

class TokenController extends Controller
{


    public function get()
    {
        $options = [
            'token' => 'test',    //填写应用接口的Token
            'encodingaeskey' => '4WGM6Jmxyqg05GXkKoNutpVSWGfRHKdwUoLzL6UeVyE',//填写加密用的EncodingAESKey
            'cropid' => 'wx6bb8b192d1dcfe19',    //填写高级调用功能的appid
            'cropsecret'=>'H4AyKWaEQiUJg7fQ5abwqrTF_QbjlnzJ8AZlPC_Ll9CiAwote4G-mXOE6C9YDNtj', //填写高级调用功能的密钥
//            'logcallback' => 'logg'
            'agentid'=>'8', //应用的id
        ];

        $app = new Server($options);
// 获取 access token 实例

        $accessToken = $app->access_token; // EasyWeChat\Core\AccessToken 实例
        $token = $accessToken->getToken(); // token 字符串
//        $token = $accessToken->getToken(true); // 强制重新从微信服务器获取 token.
return $token;
    }
}
