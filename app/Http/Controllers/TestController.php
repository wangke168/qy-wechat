<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;

class TestController extends Controller
{
    public function test()
    {
//        return 'sd';
        return $this->CheckTicket('33072419790329452x');
    }
    private function CheckTicket($DID)
    {
        $url= env('QY_WECHAT_CARD_URL', 'url');
        $url = $url . $DID;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $json = curl_exec($ch);
        $data = json_decode($json, true);
        $ticketcount = count($data['ticketorder']);
        $i = 0;

        if ($ticketcount <> 0) {
            $str = "您好，该客人的年卡信息如下";
            for ($j = 0; $j < $ticketcount; $j++) {
                $i = $i + 1;
                $str = $str . "\n订单" . $i;
                $str = $str . "\n姓名：" . $data['ticketorder'][$j]['name'];
                $str = $str . "\n年卡类型:" . $data['ticketorder'][$j]['ticket'];
                $str = $str . "\n年卡状态:" . $data['ticketorder'][$j]['content'];

                $str = $str . "\n注意：已挂失及未发卡状态的年卡无法入园，如有疑问请致电057989600055。";

            }
        } else {
            $str = "该身份证号下无年卡信息，如有疑问请致电057989600055。";
        }

        return $str;
    }
    public function test1()
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

        $options = array(
            'token' => env('QY_WECHAT_MESSAGE_TOEKN', 'token'),    //填写应用接口的Token
            'encodingaeskey' => env('QY_WECHAT_MESSAGE_ENCODINGAESKEY', 'encodingaeskey'),//填写加密用的EncodingAESKey
            'appid' => env('QY_WECHAT_APPID', 'appid'),   //填写高级调用功能的appid
            'appsecret' => env('QY_WECHAT_MESSAGE_APPSECRET', 'appsecret'),
            'debug' => env('QY_WECHAT_DEBUG', 'debug'),
            'logcallback' => env('QY_WECHAT_LOGCALLBACK', 'logcallback'),
            'agentid' => env('QY_WECHAT_MESSAGE_AGENTID', 'agentid'), //应用的id
        );
        $weObj = new \Wechat($options);

        $weObj->sendMessage($date);

      
    }
}
