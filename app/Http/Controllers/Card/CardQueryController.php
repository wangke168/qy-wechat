<?php

namespace App\Http\Controllers\Card;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wechat;
use App\Http\Requests;

class CardQueryController extends Controller
{
    public $options;

    public function __construct()
    {
        $this->options = array(
            'token' => env('QY_WECHAT_CARD_TOEKN', 'token'),  //填写应用接口的Token
            'encodingaeskey' => env('QY_WECHAT_CARD_ENCODINGAESKEY', 'encodingaeskey'), //填写加密用的EncodingAESKey
            'appid' => env('QY_WECHAT_APPID', 'appid'),   //填写高级调用功能的appid
            'appsecret' => env('QY_WECHAT_APPSECRET', 'appsecret'),
            'debug' => env('QY_WECHAT_DEBUG', 'debug'),
            'logcallback' => env('QY_WECHAT_LOGCALLBACK', 'logcallback'),
            'agentid' => env('QY_WECHAT_CARD_AGENTID', 'agentid'), //应用的id
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
            $weObj->news($this->CheckTicket($c))->reply();

        }

    }

    private function CheckTicket($DID)
    {
        $url = env('QY_WECHAT_CARD_URL', 'url');
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
            $str = "您好，该客人的年卡信息如下\n";
            $str = "姓名：".$data['ticketorder'][0]['name']."\n";
            for ($j = 0; $j < $ticketcount; $j++) {
                $i = $i + 1;
//                $str = $str . "\n种类" . $i;
//                $str = $str . "\n姓名：" . $data['ticketorder'][$j]['name'];
                $str = $str . "\n年卡类型:" . $data['ticketorder'][$j]['ticket'];
                $str = $str . "\n年卡状态:" . $data['ticketorder'][$j]['content'] . "\n";
            }
            $str = $str . "\n注意：已挂失及未发卡状态的年卡无法入园。\n\n如有疑问请致电057989600055。";
        } else {
            $str = "该身份证号下无年卡信息，如有疑问请致电057989600055。";
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
