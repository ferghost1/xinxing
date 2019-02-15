<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckRoot()) $rt->LoginPage();
if($rt->GetPost("submit"))
{
    $json =$rt->GetPost("data");
    $times=$rt->GetPost("time");

    if(!$json) 
    {
        echo "Lỗi dữ liệu - Chính Sách Tái Mua Hàng Đại Lý";
        die();
    }


    $data=json_decode($json,true);
    if(!CheckList($data)) 
    {
        echo "Lỗi dữ liệu";
        die();
    }
    $uti=new apps_libs_Utilities();
    $db=new apps_libs_Dbconn();

    $paramdele=[
        "from"=>"settingshare",
        "where"=>"year(timecreate)=".$times
    ];
    $db->Delete($paramdele);

    foreach($data as $item)
    {
        $time=$uti->GetDateMonth($item["timecreate"]);
        
        $paramdele=[
            "from"=>"settingshare",
            "where"=>"month(timecreate)=".$time["month"]." and year(timecreate)=".$time["year"] 
        ];
        $db->Delete($paramdele);

        $param=[
            "from"=>"settingshare",
            "param"=>[
                "col"=>"limitshare,timecreate",
                "data"=>[
                    $item["money"],
                    "'".$time["year"].$time["month"]."01"."'"
                ]
            ]
        ];
        $db->Insert($param);
    }
    echo "Lưu thành công";
}

function CheckList($data)
{
    $uti=new apps_libs_Utilities();
    foreach($data as $item)
    {
        if(!$item["money"]||!is_numeric($item["money"])) return false;
        if(strlen($item["timecreate"])!=7) return false;
        if(!$uti->GetDateMonth($item["timecreate"])) return false;
    }
    return true;
}