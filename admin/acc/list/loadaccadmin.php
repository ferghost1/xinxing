<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();
$number=$rt->GetPost('number');
$s = $rt->GetPost('s');
$max = $rt->GetPost('max');
if(!$max) $max=10;
if($number)
{
    if($s) {
        $page = new apps_libs_Page([
            "table" => "detailacc,acc",
            "where" => "detailacc.idacc=acc.id and acc.type='admin' and (acc.user LIKE '" . $s . "%' or detailacc.name LIKE '" . $s . "%') ORDER BY acc.user ASC",
            "col" => [
                "ID" => "acc.id",
                "Tài Khoản" => "acc.user",
                "Tên" => "detailacc.name",
                "Số Điện Thoại" => "detailacc.phonenumber",
                "Email" => "detailacc.maill",
                "Kích Hoạt" => "acc.active",
                "Thời Điểm Tạo"=>"acc.timecreate"
            ],
            "function"=>[
                "in"=>"user",
                "out"=>"id",
                "link"=>"?r=acc&p=menu&id="
            ],
            "break"=>[
                "acc.id"
            ]
        ], $number,$max, $_SERVER['QUERY_STRING']);
        echo $page->CreateTableA();
        // /echo $page->CreateListNumber();
    }
    else
    {
        $page = new apps_libs_Page([
            "table" => "detailacc,acc",
            "where" => "detailacc.idacc=acc.id and acc.type='admin' ORDER BY acc.user ASC",
            "col" => [
                "ID"=>"acc.id",
                "Tài Khoản" => "acc.user",
                "Tên" => "detailacc.name",
                "Số Điện Thoại" => "detailacc.phonenumber",
                "Email" => "detailacc.maill",
                "Kích Hoạt" => "acc.active",
                "Thời Điểm Tạo"=>"acc.timecreate"
            ],
            "function"=>[
                "in"=>"user",
                "out"=>"id",
                "link"=>"?r=acc&p=menu&id="
            ],
            "break"=>[
                "acc.id"
            ]
        ], $number,$max, $_SERVER['QUERY_STRING']);
        echo $page->CreateTableA();
        echo $page->CreateListNumberFunction();
    }
}
?>