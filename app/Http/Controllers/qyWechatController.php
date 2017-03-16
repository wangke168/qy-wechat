<?php

namespace App\Http\Controllers;


use App\qyWeChat\Wechat;
use Illuminate\Http\Request;

use App\Http\Requests;

class qyWechatController extends Controller
{
    public function test()
    {

        /*$options = array(
            'token' => 'yz43hRyIDGFUdQy3qtaZ0',    //填写应用接口的Token
            'encodingaeskey' => '4WGM6Jmxyqg05GXkKoNutpVSWGfRHKdwUoLzL6UeVyE',//填写加密用的EncodingAESKey
            'appid' => 'wx6bb8b192d1dcfe19',    //填写高级调用功能的appid
            'appsecret' => 'H4AyKWaEQiUJg7fQ5abwqrTF_QbjlnzJ8AZlPC_Ll9CiAwote4G-mXOE6C9YDNtj', //填写高级调用功能的密钥
//            'logcallback' => 'logg'
            'agentid' => '8', //应用的id

        );*/
        $options = array(
            'token' => 'yz43hRyIDGFUdQy3qtaZ0', //填写你设定的key
            'encodingaeskey' => '4WGM6Jmxyqg05GXkKoNutpVSWGfRHKdwUoLzL6UeVyE', //填写加密用的EncodingAESKey
            'appid' => 'wx6bb8b192d1dcfe19', //填写高级调用功能的app id
            'appsecret' => 'H4AyKWaEQiUJg7fQ5abwqrTF_QbjlnzJ8AZlPC_Ll9CiAwote4G-mXOE6C9YDNtj' //填写高级调用功能的密钥
        );
        $weObj = new Wechat($options);
        $weObj->valid();
        $type = $weObj->getRev()->getRevType();
        switch ($type) {
            case 'text':
                $weObj->text("hello, I'm wechat")->reply();
                exit;
                break;

            default:
                $weObj->text("help info")->reply();
        }
    }


}
