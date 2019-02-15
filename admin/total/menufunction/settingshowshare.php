<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();

$cal=new apps_calculate_calculate();
if($cal->CheckShowShare())
{
    $param=[
        "from"=>"settingshowshare",
        "where"=>1
    ];
    $db=new apps_libs_Dbconn();
    $db->Delete($param);
    echo "Xuất Doanh Thu";
}
else
{
    $param=[
        "from"=>"settingshowshare",
        "where"=>1
    ];
    $db=new apps_libs_Dbconn();
    $db->Delete($param);

    $param=[
        "from"=>"settingshowshare",
        "param"=>[
            "col"=>"timecreate,active",
            "data"=>[
                "NOW()",
                "'yes'"
            ]
        ]
            ];
    $db->Insert($param);
    echo "Ngừng Xuất Doanh Thu";
}