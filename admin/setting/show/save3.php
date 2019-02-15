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
        echo "Lỗi dữ liệu - Chính Sách Đại Lý Giới Thiệu";
        die();
    }

    $data=json_decode($json,true);
    
    $levelf1return=$data['levelf1return'];
    $levelf2f5return=$data['levelf2f5return'];
    
    if(!$levelf1return||!$levelf2f5return
    ||(!is_numeric($levelf1return)&&!is_float($levelf1return))||(!is_numeric($levelf2f5return)&&!is_float($levelf2f5return)))
    {
        echo "Lỗi dữ liệu - Chính Sách Đại Lý Giới Thiệu";
        die();
    }
    $param=[
        "from"=>"setting",
        "where"=>"id=1",
        "param"=>[
            "col"=>[
                "levelf1return",
                "levelf2f5return"
            ],
            "data"=>[
                "'".$levelf1return."'",
                "'".$levelf2f5return."'"
            ]
        ]
    ];
    $db=new apps_libs_Dbconn();
    if($db->Update($param)) echo "Lưu thành công! - Chính Sách Đại Lý Giới Thiệu";
    else echo "Lưu thất bại! - Chính Sách Đại Lý Giới Thiệu";
}

?>