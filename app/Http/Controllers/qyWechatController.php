<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Http\Requests;

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
            'debug'=>false,
            'logcallback'=>'logg'

        );
//        logg("GET参数为：\n".var_export($_GET,true));
        $weObj = new \Wechat($options);
        $ret=$weObj->valid();
        if (!$ret) {
//            logg("验证失败！");
//            var_dump($ret);
            exit;
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
            $weObj->text("你好！来自星星的：".$f."\n你发送的".$t."类型信息：\n原始信息如下：\n".var_export($c['scanresult'],true))->reply();
        }
//logg("-----------------------------------------");


    }

//检票口
    private function Check_tecket($tel)
    {
        //    $url="http://e.hengdianworld.com/searchorder_json.aspx?name=Anonymous&phone=".$tel;
//	$json=file_get_contents("http://e.hengdianworld.com/searchorder_json.aspx?name=Anonymous&phone=".$tel);

        /*
            $json=http_request_json("http://e.hengdianworld.com/searchorder_json.aspx?name=Anonymous&phone=".$tel);
            $data = json_decode($json,true);
              $ticketcount = count($data['ticketorder']);
            $inclusivecount = count($data['inclusiveorder']);
            $hotelcount = count($data['hotelorder']);
        */

        $url = "http://e.hengdianworld.com/searchorder_json.aspx?name=Anonymous&phone=".$tel;
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,10);
        $json = curl_exec($ch);
        $data = json_decode($json,true);
        $ticketcount = count($data['ticketorder']);
        $inclusivecount = count($data['inclusiveorder']);
        $hotelcount = count($data['hotelorder']);


        $i=0;

        //    $str=$str."姓名：".$name."   电话：".$tel."\n";
        if ($ticketcount<>0)
        {
            $str="您好，该客人的预订信息如下\n注意，若是联票+梦幻谷或者三点+梦幻谷的门票仍然需要身份证检票\n";
            for ($j=0; $j<$ticketcount; $j++)
            {
                $i=$i+1;
                $str=$str."\n订单".$i;
                $str=$str."\n姓名：".$data['ticketorder'][$j]['name'];
                $str=$str."\n订单号:".$data['ticketorder'][$j]['sellid'];
                $str=$str."\n预达日期:".$data['ticketorder'][$j]['date2'];
                $str=$str."\n预购景点:".$data['ticketorder'][$j]['ticket'];
                $str=$str."\n人数:".$data['ticketorder'][$j]['numbers'];
                if ($data['ticketorder'][$j]['ticket']=='三大点+梦幻谷' || $data['ticketorder'][$j]['ticket']=='网络联票+梦幻谷')
                {
                    $str=$str."\n注意：该票种需要身份证检票";
                }
                else
                {
                    $str=$str."\n订单识别码:".$data['ticketorder'][$j]['code']."（在检票口出示此识别码可直接进入景区。）";
                }
                $str=$str."\n订单状态:".$data['ticketorder'][$j]['flag']."\n";
            }
        }
        else
        {
            $str="该手机号下无门票订单";
        }
        $newsData= array(
            "0"=>array(
                'Title'=>'查询结果',
                'Description'=>$str,
//	   		'PicUrl'=>'http://qydev.weixin.qq.com/wiki/skins/common/images/weixin/weixin_wiki_logo.png',
                'Url'=>'http://weix2.hengdianworld.com/article/articledetail.php?id=44'
            )
        );
        return $newsData;
    }

}
