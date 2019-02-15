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
        echo "Lỗi dữ liệu - Chính Sách Tái Mua Hàng Đại Lý";
        die();
    }


    $data=json_decode($json,true);
    
    $extractmax=$data['extractmax'];
    $list=$data['list'];
    
    if(!$extractmax||!CheckList($list)
    ||(!is_numeric($extractmax)&&!is_float($extractmax)))  
    {
        echo "Lỗi dữ liệu - Chính Sách Tái Mua Hàng Đại Lý";
        die();
    }

    
    $param=[
        "from"=>"setting",
        "where"=>"id=1",
        "param"=>[
            "col"=>[
                "extractmax"
            ],
            "data"=>[
                "'".$extractmax."'",
            ]
        ]
    ];
    $db=new apps_libs_Dbconn();
    if($db->Update($param)) 
    {
        $paramdel=[
            "from"=>"settingreceived",
            "where"=>"1"
        ];
        $db->Delete($paramdel);

        foreach($list as $item)
        {
            $param2=[
                "from"=>"settingreceived",
                "param"=>[
                    "col"=>"money,percentsend",
                    "data"=>[
                        $item["money"],
                        $item["percentsend"]
                    ]
                ]
            ];
            $db->Insert($param2);
        }

        echo "Lưu thành công! - Chính Sách Tái Mua Hàng Đại Lý";
    }
    else echo "Lưu thất bại! - Chính Sách Tái Mua Hàng Đại Lý";
}


function CheckList($list)
{
    if($list)
        foreach($list as $item)
        {
            if(!is_numeric($item["money"])) return false;
            if(!is_numeric($item["percentsend"])&&!is_float($item["percentsend"])) return false;
        }
    else return false;
    return true;
}

?>