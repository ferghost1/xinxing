<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();;

if($rt->GetPost('id'))
{
    
    $id=$rt->GetPost('id');
    $index=$rt->GetPost('number');
    $s=$rt->GetPost('s');
    $max = $rt->GetPost('max');
    if(!$max) $max=10;  
    
    $breakrow = FindBreakRow($id);

    if($s)
    {
        $page = new apps_libs_Page([
            "table" => "detailacc,acc",
            "where" => "detailacc.idacc=acc.id and acc.type!='admin' and acc.id!='".$id."' and (acc.user LIKE '".$s."%' or detailacc.name LIKE '".$s."%')",
            "col" => [
                "ID"=>"acc.id",
                "Tài Khoản" => "acc.user",
                "Tên" => "detailacc.name"
            ],
            "function"=>[
                "in"=>"user",
                "out"=>"id"
            ],
            "break"=>[
                "acc.id"
            ],
            "breakrow"=>$breakrow=null
        ], 1,$max, $_SERVER['QUERY_STRING']);
        echo $page->CreateTableFunction();
        die();
    }

    $page = new apps_libs_Page([
        "table" => "detailacc,acc",
        "where" => "detailacc.idacc=acc.id and acc.type!='admin' and acc.id!='".$id."'",
        "col" => [
            "ID"=>"acc.id",
            "Tài Khoản" => "acc.user",
            "Tên" => "detailacc.name"
        ],
        "function"=>[
            "in"=>"user",
            "out"=>"id"
        ],
        "break"=>[
            "acc.id"
        ],
        "breakrow"=>$breakrow
    ], $index,$max, $_SERVER['QUERY_STRING']);
    echo $page->CreateTableFunction();
    echo $page->CreateListNumberFunction();
}


function FindBreakRow($id)
{
    $data;
    $param=[
        "select"=>"dadacc",
        "from"=>"relationshipacc",
        "where"=>"children=\"".$id."\""
    ];
    $db=new apps_libs_Dbconn();
    $result=$db->Select($param);

    $i=0;
    while($row=mysqli_fetch_assoc($result))
    {
        $data[$i]=$row["dadacc"];
        $i++;
    }

    $param=[
        "select"=>"children",
        "from"=>"relationshipacc",
        "where"=>"dadacc=\"".$id."\""
    ];
    $db=new apps_libs_Dbconn();
    $result=$db->Select($param);

    while($row=mysqli_fetch_assoc($result))
    {
        $data[$i]=$row["children"];
        $i++;
    }

    return $data;
}