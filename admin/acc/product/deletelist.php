<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
$db=new apps_libs_Dbconn();

if (!$user->CheckAdmin()) $rt->LoginPage();
$id = $rt->GetPost("id");
// Lấy lại % tái mua từ đơn, trả lại tái mua đã dùng cho acc
$sql = "update acc, historyproduct as his 
			set acc.repurchase_money = acc.repurchase_money + his.repurchase_money - his.repurchase_add 
			where acc.id = his.idacc 
				and his.id = '{$id}'";

$db->voidQuery($sql);

$delete=[
    "from"=>"historyproduct",
    "where"=>"id='".$id."'"
];

$db->Delete($delete);

$delete1=[
    "from"=>"listproduct",
    "where"=>"idhp='".$id."'"
];
$db->Delete($delete1);

echo json_encode(array(0,"Xóa Thành Công !"));
?>