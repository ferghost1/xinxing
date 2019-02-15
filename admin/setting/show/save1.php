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
        echo "Lỗi dữ liệu - Cài Đặt Chia Lợi Nhuận";
        die();
    }

    $data=json_decode($json,true);
    
    $accumulate=$data['accumulate'];
    $percentcompanyreturn=$data['percentcompanyreturn'];
    $limitshare=$data['limitshare'];
    
    if(!$accumulate||!$percentcompanyreturn||!$limitshare
    ||!is_numeric($accumulate)||(!is_numeric($percentcompanyreturn)&&!is_float($percentcompanyreturn))||!is_numeric($limitshare))      
    {
        echo "Lỗi dữ liệu - Cài Đặt Chia Lợi Nhuận";
        die();
    }
    
    $param=[
        "from"=>"setting",
        "where"=>"id=1",
        "param"=>[
            "col"=>[
                "accumulate",
                "percentcompanyreturn",
                "limitshare"
            ],
            "data"=>[
                "'".$accumulate."'",
                "'".$percentcompanyreturn."'",
                "'".$limitshare."'"
            ]
        ]
    ];
    $db=new apps_libs_Dbconn();
    if($db->Update($param)) echo "Lưu thành công! - Cài Đặt Chia Lợi Nhuận";
    else echo "Lưu thất bại! - Cài Đặt Chia Lợi Nhuận";
}

?>