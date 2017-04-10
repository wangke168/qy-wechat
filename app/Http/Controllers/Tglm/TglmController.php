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
            'encodingaeskey' => 'UwZuYaFyIM0LKrWhDzdEscKauy6sAU0whvDJjCH65OE',//填写加密用的EncodingAESKey
            'appid' => 'wx6bb8b192d1dcfe19',    //填写高级调用功能的appid
            'appsecret' => '8-ma2YdRHCrYsBl5Mw0vSFEegXTyxMk1OCQQzfBIhnwOrWY6rnCjXb0Nv0pz5Pea',
            'debug' => true,
            'logcallback' => 'logg',
            'agentid' => '7', //应用的id
        );
    }
    public function index()
    {
/*        $options = array(
            'token' => 'tuiguanglianmeng',    //填写应用接口的Token
            'encodingaeskey' => 'UwZuYaFyIM0LKrWhDzdEscKauy6sAU0whvDJjCH65OE',//填写加密用的EncodingAESKey
            'appid' => 'wx6bb8b192d1dcfe19',    //填写高级调用功能的appid
            'appsecret' => '8-ma2YdRHCrYsBl5Mw0vSFEegXTyxMk1OCQQzfBIhnwOrWY6rnCjXb0Nv0pz5Pea',
            'debug' => true,
            'logcallback' => 'logg',
            'agentid' => '7', //应用的id

        );*/
//        logg("GET参数为：\n".var_export($_GET,true));
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
                // $weObj->text($evnet_type['event'])->reply();

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
