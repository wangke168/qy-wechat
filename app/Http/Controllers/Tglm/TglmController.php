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
                    'PicUrl' => "http://" . $_SERVER['HTTP_HOST'] . "/images/htgl.jpg",
                    'Url' => 'http://e.hengdianworld.com/mobile/Agent/agent.aspx?uid=' . $uid
                )
            );
        } else {
            $newsData = array(
                "0" => array(
                    'Title' => '请先等待申请通过',
                    'Description' => '您需要先申请开通推广联盟帐号。',
                    'PicUrl' => "http://" . $_SERVER['HTTP_HOST'] . "/images/nsqlm.jpg",
                    'Url' => 'http://weix4.hengdianworld.com/article/articledetail.php?id=139'
                )
            );
        }
        return $newsData;
    }

    public function sendmessage($sellid)
    {

    }
}
