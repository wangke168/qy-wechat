<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
class qyWechatController extends Controller
{
    public function test()
    {
      //  include(app_path() . 'app/qyWechat/qyWechat.php');
        /*$options = array(
            'token' => 'yz43hRyIDGFUdQy3qtaZ0',    //填写应用接口的Token
            'encodingaeskey' => '4WGM6Jmxyqg05GXkKoNutpVSWGfRHKdwUoLzL6UeVyE',//填写加密用的EncodingAESKey
            'appid' => 'wx6bb8b192d1dcfe19',    //填写高级调用功能的appid
            'appsecret' => 'H4AyKWaEQiUJg7fQ5abwqrTF_QbjlnzJ8AZlPC_Ll9CiAwote4G-mXOE6C9YDNtj', //填写高级调用功能的密钥
//            'logcallback' => 'logg'
            'agentid' => '8', //应用的id

        );*/

        $options = array(
            'token'=>'yz43hRyIDGFUdQy3qtaZ0',	//填写应用接口的Token
            'encodingaeskey'=>'Eu6T9BjvcKn3m2s2DR87uCAE1M4tnbrqXdJ6nTb7DMh',//填写加密用的EncodingAESKey
            'appid'=>'wx6bb8b192d1dcfe19',	//填写高级调用功能的appid
            'appsecret'=>'H4AyKWaEQiUJg7fQ5abwqrTF_QbjlnzJ8AZlPC_Ll9CiAwote4G-mXOE6C9YDNtj',
            'debug'=>true,
            'logcallback'=>'logg',
            'agentid' => '8', //应用的id

        );
//        logg("GET参数为：\n".var_export($_GET,true));
        $weObj = new \Wechat($options);
        $ret=$weObj->valid();
        if (!$ret) {
            \Log::info($ret);
        }
        /*
        $f = $weObj->getRev()->getRevFrom();	//获取发送者微信号
        $t = $weObj->getRevType();				//获取发送的类型
        $d = $weObj->getRevData();				//获取发送的data
        if ($t=="text")
        {
            $c = $weObj->getRevContent();			//获取发送的内容
            $weObj->news(Check_tecket($c))->reply();
        }
        */

        $f = $weObj->getRev()->getRevFrom();	//获取发送者微信号
        $t = $weObj->getRevType();				//获取发送的类型
        $d = $weObj->getRevData();				//获取发送的data
        $c = $weObj->getRevContent();			//获取发送的内容
        if ($t=="text")
        {
//            $c = $weObj->getRevContent();			//获取发送的内容
//           $weObj->text("你好！来自星星的：")->reply();
//            $c = $weObj->getRevContent();			//获取发送的内容
//            $weObj->news($this->Check_tecket($c))->reply();
            $weObj->sendMessage($this->Check_tecket($c));

        }
//logg("-----------------------------------------");
//        $weObj->valid();


    }

//检票口
    private function Check_tecket($tel)
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
            'msgtype' => 'news',  //根据信息类型，选择下面对应的信息结构体

            "news" => array(            //不支持保密
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

        return $date;
   /*     $options = array(
            'token' => env('QY_WECHAT_MESSAGE_TOEKN', 'token'),    //填写应用接口的Token
            'encodingaeskey' => env('QY_WECHAT_MESSAGE_ENCODINGAESKEY', 'encodingaeskey'),//填写加密用的EncodingAESKey
            'appid' => env('QY_WECHAT_APPID', 'appid'),   //填写高级调用功能的appid
            'appsecret' => env('QY_WECHAT_APPSECRET', 'appsecret'),
            'debug' => env('QY_WECHAT_DEBUG', 'debug'),
            'logcallback' => env('QY_WECHAT_LOGCALLBACK', 'logcallback'),
        );
        $weObj = new \Wechat($options);
        $weObj->sendMessage($date);*/
    }

}
