<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendLdjlMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendLdjlMessage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'SendLdjlMessage';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
 /*       $row1 = $db->row("select count(*) as allcount from tour_project_wait_detail where project_id=:project_id and date(addtime)=:addtime",
            array("project_id" => "1", "addtime" => date("Y-m-d")));

        $row2 = $db->row("select count(*) as hxcount from tour_project_wait_detail where project_id=:project_id and used=:used and date(addtime)=:addtime",
            array("project_id" => "1", "used" => "1", "addtime" => date("Y-m-d")));

        @$allcount = $row1["allcount"];

        $hxcount = $row2["hxcount"];*/


        $allcount=100;
        $hxcount=50;
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
                        "picurl" => "http://weix.hengdianworld.com/control/editor/attached/image/20160412/20160412103944_30797.jpg",
                    ),
                ]
            ),

        );

        $options = array(
            'token' => 'message',    //填写应用接口的Token
            'encodingaeskey' => 'pgqNTJsXjdzDOaP0DD1jlzyW5sqNIR9anCX247GR1zf',//填写加密用的EncodingAESKey
            'appid' => 'wx6bb8b192d1dcfe19',    //填写高级调用功能的appid
            'appsecret' => '8-ma2YdRHCrYsBl5Mw0vSFEegXTyxMk1OCQQzfBIhnwOrWY6rnCjXb0Nv0pz5Pea',
            'debug' => true,
            'logcallback' => 'logg',
//    'Secret' => '8-ma2YdRHCrYsBl5Mw0vSFEegXTyxMk1OCQQzfBIhnwOrWY6rnCjXb0Nv0pz5Pea'

        );
        $aaa = new \Wechat($options);
        $aaa->sendMessage($date);
    }
}
