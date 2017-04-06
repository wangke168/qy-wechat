<?php

namespace App\Http\Controllers\Tglm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

class TglmController extends Controller
{
	public function index()
	{
		$options = array(
            'token'=>'tuiguanglianmeng',	//填写应用接口的Token
            'encodingaeskey'=>'UwZuYaFyIM0LKrWhDzdEscKauy6sAU0whvDJjCH65OE',//填写加密用的EncodingAESKey
            'appid'=>'wx6bb8b192d1dcfe19',	//填写高级调用功能的appid
            'appsecret'=>'8-ma2YdRHCrYsBl5Mw0vSFEegXTyxMk1OCQQzfBIhnwOrWY6rnCjXb0Nv0pz5Pea',
            'debug'=>true,
            'logcallback'=>'logg',
            'agentid' => '7', //应用的id

            );
//        logg("GET参数为：\n".var_export($_GET,true));
		$weObj = new \Wechat($options);
		$ret=$weObj->valid();
		if (!$ret) {
			\Log::info($ret);
		}

        $f = $weObj->getRev()->getRevFrom();	//获取发送者微信号
        $type = $weObj->getRevType();				//获取发送的类型
        $d = $weObj->getRevData();				//获取发送的data
        $c = $weObj->getRevContent();			//获取发送的内容

        switch ($type) {
        	case 'event':

        				$weObj->text($weObj->getRevEvent())->reply();
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
    }
