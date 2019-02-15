<?php
include_once('../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();
$id=$rt->GetPost("id");

$param=[
    "select"=>"acc.user,detailacc.name,detailacc.linkimg",
    "from"=>"acc,detailacc",
    "where"=>"acc.id=detailacc.idacc and acc.id='".$id."'"
];
$db=new apps_libs_Dbconn();
$result=$db->SelectOne($param);
$row=mysqli_fetch_assoc($result);
$data=[
    "user"=>$row["user"],
    "nameuser"=>$row["name"],
    "imguser"=>$row["linkimg"]==""?"/admin/img/avata/default.png":$row["linkimg"]
];

echo json_encode($data);
?>