<?php

namespace App\Http\Controllers\JianPiao;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Wechat;

class JianPiaoController extends Controller
{
    public $options;

    public function __construct()
    {
        $this->options = array(
            'token' => env('QY_WECHAT_JIANPIAO_TOEKN', 'token'),  //填写应用接口的Token
            'encodingaeskey' => env('QY_WECHAT_JIANPIAO_ENCODINGAESKEY', 'encodingaeskey'), //填写加密用的EncodingAESKey
            'appid' => env('QY_WECHAT_APPID', 'appid'),   //填写高级调用功能的appid
            'appsecret' => env('QY_WECHAT_APPSECRET', 'appsecret'),
            'debug' => env('QY_WECHAT_DEBUG', 'debug'),
            'logcallback' => env('QY_WECHAT_LOGCALLBACK', 'logcallback'),
            'agentid' => env('QY_WECHAT_JIANPIAO_AGENTID', 'agentid'), //应用的id
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
        $t = $weObj->getRevType();                //获取发送的类型
        $d = $weObj->getRevData();                //获取发送的data
        $c = $weObj->getRevContent();            //获取发送的内容
        if ($t == "text") {
            $weObj->news($this->Check_tecket($c))->reply();

        }

    }

    private function Check_tecket($tel)
    {
        $url= env('QY_WECHAT_JIANPIAO_URL', 'url');
        $url = $url . $tel;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $json = curl_exec($ch);
        $data = json_decode($json, true);
        $ticketcount = count($data['ticketorder']);
        $inclusivecount = count($data['inclusiveorder']);
        $hotelcount = count($data['hotelorder']);


        $i = 0;

        //    $str=$str."姓名：".$name."   电话：".$tel."\n";
        if ($ticketcount <> 0) {
//            $str = "您好，该客人的预订信息如下\n注意，若是联票+梦幻谷或者三点+梦幻谷的门票仍然需要身份证检票\n";
            $str = "您好，该客人的预订信息如下";
            for ($j = 0; $j < $ticketcount; $j++) {
                $i = $i + 1;
                $str = $str . "\n订单" . $i;
                $str = $str . "\n姓名：" . $data['ticketorder'][$j]['name'];
                $str = $str . "\n订单号:" . $data['ticketorder'][$j]['sellid'];
                $str = $str . "\n预达日期:" . $data['ticketorder'][$j]['date2'];
                $str = $str . "\n预购景点:" . $data['ticketorder'][$j]['ticket'];
                $str = $str . "\n人数:" . $data['ticketorder'][$j]['numbers'];
               /* if ($data['ticketorder'][$j]['ticket'] == '三大点+梦幻谷' || $data['ticketorder'][$j]['ticket'] == '网络联票+梦幻谷') {
                    $str = $str . "\n注意：该票种需要身份证检票";
                } else {*/
                    $str = $str . "\n订单识别码:" . $data['ticketorder'][$j]['code'] . "（在检票口出示此识别码可直接进入景区。）";
//                }
                $str = $str . "\n订单状态:" . $data['ticketorder'][$j]['flag'] . "\n";
            }
        } else {
            $str = "该手机号下无门票订单";
        }
        $newsData = array(
            "0" => array(
                'Title' => '查询结果',
                'Description' => $str,
                'Url' => 'https://wechat.hdyuanmingxinyuan.com/article/detail?id=1482'
            )
        );
        return $newsData;
    }
}
