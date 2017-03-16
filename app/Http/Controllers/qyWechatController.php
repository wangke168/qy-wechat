<?php

namespace App\Http\Controllers;

//use App\qyWeChat\qyWechat;
use Illuminate\Http\Request;
use Stoneworld\Wechat\Server;
use Stoneworld\Wechat\Message;
use EasyWeChat\Foundation\Application;
use App\Http\Requests;

class qyWechatController extends Controller
{
  public function test()
  {
    $options = array(
            'token' => 'B4epG0xkXrBd5ZJINwTY',    //填写应用接口的Token
            'encodingaeskey' => '4WGM6Jmxyqg05GXkKoNutpVSWGfRHKdwUoLzL6UeVyE',//填写加密用的EncodingAESKey
            'appid' => 'wx6bb8b192d1dcfe19',    //填写高级调用功能的appid
            'appsecret'=>'H4AyKWaEQiUJg7fQ5abwqrTF_QbjlnzJ8AZlPC_Ll9CiAwote4G-mXOE6C9YDNtj', //填写高级调用功能的密钥
//            'logcallback' => 'logg'
            'agentid'=>'8', //应用的id

            );
//        logg("GET参数为：\n".var_export($_GET,true));
      $server = new Server($options);

      // 监听所有类型
      $server->on('message', function($message) {
          return Message::make('text')->content('您好！');
      });

// 监听指定类型
      $server->on('message', 'image', function($message) {
          return Message::make('text')->content('我们已经收到您发送的图片！');
      });

      $result = $server->server();

      echo $result;

    }

}
