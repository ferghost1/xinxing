<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckRoot()) $rt->LoginPage();
if($rt->GetPost("submit"))
{
    $json =$rt->GetPost("data");

    if(!$json) 
    {
        echo "Lỗi dữ liệu - Chính Sách Tái Mua Hàng";
        die();
    }
    $data=json_decode($json,true);
    
    $firstreturnshare=$data['firstreturnshare'];
    $nextreturnshare=$data['nextreturnshare'];
    $limitreturnshare=$data['limitreturnshare'];
    
    if(!$firstreturnshare||!$nextreturnshare||!$limitreturnshare
    ||(!is_numeric($firstreturnshare)&&!is_float($firstreturnshare))||(!is_numeric($nextreturnshare)&&!is_float($nextreturnshare))||(!is_numeric($limitreturnshare)&&!is_float($limitreturnshare)))
    {
        echo "Lỗi dữ liệu - Chính Sách Tái Mua Hàng";
        die();
    }
    
    $param=[
        "from"=>"setting",
        "where"=>"id=1",
        "param"=>[
            "col"=>[
                "firstreturnshare",
                "nextreturnshare",
                "limitreturnshare"
            ],
            "data"=>[
                "'".$firstreturnshare."'",
                "'".$nextreturnshare."'",
                "'".$limitreturnshare."'"
            ]
        ]
    ];
    $db=new apps_libs_Dbconn();
    if($db->Update($param)) echo "Lưu thành công! - Chính Sách Tái Mua Hàng";
    else echo "Lưu thất bại! - Chính Sách Tái Mua Hàng";
}

?>