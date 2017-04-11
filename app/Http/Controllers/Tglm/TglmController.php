<?php

namespace App\Http\Controllers\Tglm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Wechat;
use DB;

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
                        switch ($evnet_type['key']) {
                            case "3001":
                                $weObj->news($this->response_manage($f))->reply();
                                break;
                            default:
                                $weObj->text("你好！功能正在升级中，请稍后尝试")->reply();
                                break;
                        }

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

    private function response_manage($f)

    {


 /*       $row = $db->row("select * from wx_qrscene_info WHERE qrscene_id = ( SELECT eventkey FROM qyh_user_info WHERE userid =:userid )",
            array("userid" => $f));*/

        $row=DB::table('wx_qrscene_info')
            ->join('qyh_user_info',function($join)use($f){
                $join->on('wx_qrscene_info.qrscene_id','=','qyh_user_info.eventkey')
                    ->where('qyh_user_info.userid','=',$f);
            })->first();
        @$uid = $row->uid;
        if ($uid) {
            $newsData = array(
                "0" => array(
                    'Title' => '点击进入管理后台',
                    'Description' => '',
                    'PicUrl' => "https://" . $_SERVER['HTTP_HOST'] . "/images/htgl.jpg",
                    'Url' => 'http://e.hengdianworld.com/mobile/Agent/agent.aspx?uid=' . $uid
                )
            );
        } else {
            $newsData = array(
                "0" => array(
                    'Title' => '请先等待申请通过',
                    'Description' => '您需要先申请开通推广联盟帐号。',
                    'PicUrl' => "https://" . $_SERVER['HTTP_HOST'] . "/images/nsqlm.jpg",
                    'Url' => 'http://weix4.hengdianworld.com/article/articledetail.php?id=139'
                )
            );
        }
        return $newsData;
    }

    public function sendmessage(Request $request)
    {
        $sellid=$request->input('sellid');
        $eventkey=$request->input('sellid');
       $this->Check_tecket($sellid,'thin_pig');
    }

    private function Check_tecket($sellid,$userid)
    {
        $url = "http://e.hengdianworld.com/searchorder_json.aspx?sellid=" . $sellid;
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
            $title='您有新门票订单';
            //      for ($j = 0; $j < $ticketcount; $j++) {
            //       $i = $i + 1;
            $str = "订单详情";
            //    $str = $str . "\n姓名：" . $data['ticketorder'][$j]['name'];
            $str = $str . "\n订单号:" . $data['ticketorder'][0]['sellid'];
            //    $str = $str . "\n预达日期:" . $data['ticketorder'][$j]['date2'];
            $str = $str . "\n预购景点:" . $data['ticketorder'][0]['ticket'];
            $str = $str . "\n人数:" . $data['ticketorder'][0]['numbers'];
            /*        if ($data['ticketorder'][$j]['ticket'] == '三大点+梦幻谷' || $data['ticketorder'][$j]['ticket'] == '网络联票+梦幻谷') {
                        $str = $str . "\n注意：该票种需要身份证检票";
                    } else {
                        $str = $str . "\n订单识别码:" . $data['ticketorder'][$j]['code'] . "（在检票口出示此识别码可直接进入景区。）";
                    }*/
            //      $str = $str . "\n订单状态:" . $data['ticketorder'][$j]['flag'] . "\n";
            //       }
        }
        elseif($inclusivecount<>0)
        {
            $title='您有新套餐订单';
//            $i = $i + 1;
            $str = "订单详情";
            //    $str = $str . "\n姓名：" . $data['ticketorder'][0]['name'];
            $str = $str . "\n订单号:" . $data['inclusiveorder'][0]['sellid'];
            //    $str = $str . "\n预达日期:" . $data['inclusiveorder'][0]['date2'];
            $str = $str . "\n预购景点:" . $data['inclusiveorder'][0]['ticket'];
            $str = $str . "\n人数:" . $data['inclusiveorder'][0]['numbers'];
        }
        elseif($hotelcount<>0)
        {
            $title='您有新酒店订单';
            $str = "订单详情";
            //    $str = $str . "\n姓名：" . $data['ticketorder'][0]['name'];
            $str = $str . "\n订单号:" . $data['$hotelcount'][0]['sellid'];
            //    $str = $str . "\n预达日期:" . $data['inclusiveorder'][0]['date2'];
            $str = $str . "\n入住酒店:" . $data['$hotelcount'][0]['hotel'];
            $str = $str . "\n间数:" . $data['$hotelcount'][0]['numbers'];
            $str = $str . "\n天数:" . $data['$hotelcount'][0]['days'];

        } $i = 0;

        //    $str=$str."姓名：".$name."   电话：".$tel."\n";
        if ($ticketcount <> 0) {
            $title='您有新门票订单';
            //      for ($j = 0; $j < $ticketcount; $j++) {
            //       $i = $i + 1;
            $str = "订单详情";
            //    $str = $str . "\n姓名：" . $data['ticketorder'][$j]['name'];
            $str = $str . "\n订单号:" . $data['ticketorder'][0]['sellid'];
            //    $str = $str . "\n预达日期:" . $data['ticketorder'][$j]['date2'];
            $str = $str . "\n预购景点:" . $data['ticketorder'][0]['ticket'];
            $str = $str . "\n人数:" . $data['ticketorder'][0]['numbers'];
            /*        if ($data['ticketorder'][$j]['ticket'] == '三大点+梦幻谷' || $data['ticketorder'][$j]['ticket'] == '网络联票+梦幻谷') {
                        $str = $str . "\n注意：该票种需要身份证检票";
                    } else {
                        $str = $str . "\n订单识别码:" . $data['ticketorder'][$j]['code'] . "（在检票口出示此识别码可直接进入景区。）";
                    }*/
            //      $str = $str . "\n订单状态:" . $data['ticketorder'][$j]['flag'] . "\n";
            //       }
        }
        elseif($inclusivecount<>0)
        {
            $title='您有新套餐订单';
//            $i = $i + 1;
            $str = "订单详情";
            //    $str = $str . "\n姓名：" . $data['ticketorder'][0]['name'];
            $str = $str . "\n订单号:" . $data['inclusiveorder'][0]['sellid'];
            //    $str = $str . "\n预达日期:" . $data['inclusiveorder'][0]['date2'];
            $str = $str . "\n预购景点:" . $data['inclusiveorder'][0]['ticket'];
            $str = $str . "\n人数:" . $data['inclusiveorder'][0]['numbers'];
        }
        elseif($hotelcount<>0)
        {
            $title='您有新酒店订单';
            $str = "订单详情";
            //    $str = $str . "\n姓名：" . $data['ticketorder'][0]['name'];
            $str = $str . "\n订单号:" . $data['$hotelcount'][0]['sellid'];
            //    $str = $str . "\n预达日期:" . $data['inclusiveorder'][0]['date2'];
            $str = $str . "\n入住酒店:" . $data['$hotelcount'][0]['hotel'];
            $str = $str . "\n间数:" . $data['$hotelcount'][0]['numbers'];
            $str = $str . "\n天数:" . $data['$hotelcount'][0]['days'];

        }

        $newsData = array(
            "0" => array(
                'Title' => '查询结果',
                'Description' => $str,
                'Url' => 'http://weix2.hengdianworld.com/article/articledetail.php?id=44'
            )
        );

        $date = array(
            'touser' => $userid,
            "toparty" => "",
            "totag" => "",
            'agentid' => '7',    //应用id
            'msgtype' => 'news',  //根据信息类型，选择下面对应的信息结构体

            "news" => array(            //不支持保密
                "articles" => [
                    array(
                        "title" => $title,
                        "description" => $str,
                        "url" => "http://weix2.hengdianworld.com/enterprise/article/articledetail_ldjl.php?date=".date("Y-m-d"),
                  //      "picurl" => "https://weix.hengdianworld.com/images/ldjl_data.jpg",
                    ),
                ]
            ),

        );
        $weObj = new Wechat($this->options);
//        $weObj->news($this->Check_tecket($sellid))->reply();
        $weObj->sendMessage($date);
//        return $newsData;
    }

}
