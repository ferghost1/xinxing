<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckRoot()) $rt->LoginPage();

$db=new apps_libs_Dbconn();

$param=[
    "select"=>"levelf1return,levelf2f5return",
    "from"=>"setting",
    "where"=>"id=1"
];
$result=$db->SelectOne($param);
if($result)
{
    $row=mysqli_fetch_assoc($result);
    $data=[
        "levelf1return"=>$row["levelf1return"],
        "levelf2f5return"=>$row["levelf2f5return"]
    ];
    echo json_encode($data);
}
else 
{
    $data=[
        "levelf1return"=>"",
        "levelf2f5return"=>""
    ];
    echo json_encode($data);
}
?>