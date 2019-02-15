<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckRoot()) $rt->LoginPage();

$db=new apps_libs_Dbconn();

$param=[
    "select"=>"firstreturnshare,nextreturnshare,limitreturnshare",
    "from"=>"setting",
    "where"=>"id=1"
];
$result=$db->SelectOne($param);
if($result)
{
    $row=mysqli_fetch_assoc($result);
    $data=[
        "firstreturnshare"=>$row["firstreturnshare"],
        "nextreturnshare"=>$row["nextreturnshare"],
        "limitreturnshare"=>$row["limitreturnshare"]
    ];
    echo json_encode($data);
}
else 
{
    $data=[
        "firstreturnshare"=>"",
        "nextreturnshare"=>"",
        "limitreturnshare"=>""
    ];
    echo json_encode($data);
}
?>