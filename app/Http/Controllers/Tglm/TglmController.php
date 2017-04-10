<?php

namespace App\Http\Controllers\Tglm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Wechat;

class TglmController extends Controller
{
    public $options;

    public function __construct()
    {
        $this->options = array(
            'token' => env('QY_WECHAT_TGLM_TOEKN', 'token'),  //填写应用接口的Token
            'encodingaeskey' => env('QY_WECHAT_TGLM_ENCODINGAESKEY', 'encodingaeskey'), //填写加密用的EncodingAESKey
            'appid' => env('QY_WECHAT_APPID', 'appid'),   //填写高级调用功能的appid
            'appsecret' => env('QY_WECHAT_APPSECRET', 'appsecret'),
            'debug' => env('QY_WECHAT_DEBUG', 'debug'),
            'logcallback' => env('QY_WECHAT_LOGCALLBACK', 'logcallback'),
            'agentid' => env('QY_WECHAT_TGLM_AGENTID', 'agentid'), //应用的id
        );
    }

    public function index()
    {

        $weObj = new Wechat($this->options);
        $ret = $weObj->valid();
        if (!$ret) {
            \Log::info($ret);
        }

        $f = $weObj->getRev()->getRevFrom();    //获取发送者微信号
        $type = $weObj->getRevType();                //获取发送的类型
        $d = $weObj->getRevData();                //获取发送的data
        $c = $weObj->getRevContent();            //获取发送的内容

        switch ($type) {
            case 'event':
                $evnet_type = $weObj->getRevEvent();
                switch ($evnet_type['event']) {
                    case 'click':
                        $weObj->text("你好！功能正在升级中，请稍后尝试")->reply();
                        // $weObj->news(response_news($evnet_type['key']))->reply();
                        break;
                    default:
                        // 		# code...
                        break;
                }
                break;
            case 'text':
                $weObj->text("你好！功能正在升级中，请稍后尝试")->reply();
                break;
            default:
                # code...
                break;
        }

    }

    public function sendmessage($sellid)
    {

    }
}
