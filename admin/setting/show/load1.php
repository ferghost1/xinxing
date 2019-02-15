<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckRoot()) $rt->LoginPage();

$db=new apps_libs_Dbconn();

$param=[
    "select"=>"accumulate,percentcompanyreturn,limitshare",
    "from"=>"setting",
    "where"=>"id=1"
];
$result=$db->SelectOne($param);
if($result)
{
    $row=mysqli_fetch_assoc($result);
    $data=[
        "accumulate"=>$row["accumulate"],
        "percentcompanyreturn"=>$row["percentcompanyreturn"],
        "limitshare"=>$row["limitshare"]
    ];
    echo json_encode($data);
}
else 
{
    $data=[
        "accumulate"=>"",
        "percentcompanyreturn"=>"",
        "limitshare"=>""
    ];
    echo json_encode($data);
}
?>