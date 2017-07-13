<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Wechat;
class TestController extends Controller
{
    public function test()
    {
        $allcount=DB::table('tour_project_wait_detail')
            ->where('project_id','1')
            ->whereDate('addtime','=',date("Y-m-d"))
            ->count();
        $hxcount=DB::table('tour_project_wait_detail')
            ->where('project_id','1')
            ->where('used','1')
            ->whereDate('addtime','=',date("Y-m-d"))
            ->count();
        $title = date("Y-m-d") . "龙帝惊临微信预约数据汇总";

        $Description = "今天总预约人数为" . $allcount . ",核销人数为" . $hxcount . "。";

        $date = array(
            'touser' => 'thin_pig',
            "toparty" => "",
            "totag" => "",
            'agentid' => '6',    //应用id
            'msgtype' => 'mpnews',  //根据信息类型，选择下面对应的信息结构体

            "mpnews" => array(            //不支持保密
                "articles" => [
                    array(
                        "title" => $title,
                        "description" => $Description,
                        "url" => "http://weix2.hengdianworld.com/enterprise/article/articledetail_ldjl.php?date=".date("Y-m-d"),
                        "picurl" => "https://weix.hengdianworld.com/images/ldjl_data.jpg",
                    ),
                ]
            ),

        );

        $options = array(
            'token' => env('QY_WECHAT_MESSAGE_TOEKN', 'token'),    //填写应用接口的Token
            'encodingaeskey' => env('QY_WECHAT_MESSAGE_ENCODINGAESKEY', 'encodingaeskey'),//填写加密用的EncodingAESKey
            'appid' => env('QY_WECHAT_APPID', 'appid'),   //填写高级调用功能的appid
            'appsecret' => env('QY_WECHAT_APPSECRET', 'appsecret'),
            'debug' => env('QY_WECHAT_DEBUG', 'debug'),
            'logcallback' => env('QY_WECHAT_LOGCALLBACK', 'logcallback'),
        );
        $weObj = new \Wechat($options);

        var_dump($weObj->sendMessage($date));

        var_dump($date);
    }
}
