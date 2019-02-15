<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
$db = new apps_libs_Dbconn();
if (!$user->CheckRoot()) $rt->LoginPage();
if($rt->GetPost("submit_revenue"))
{	
	$revenue_rate = $_POST['revenue'];
	$repurchase_rate = $_POST['repurchase_rate'];
	foreach($revenue_rate as $k => $v){
		if(empty($v))
			$revenue_rate[$k] = 0;
	}
	$revenue_rate = json_encode($revenue_rate);
	$sql = "update revenue_share set revenue = '{$revenue_rate}', repurchase_rate = {$repurchase_rate}, agency_rate = {$_POST['agency_rate']}, agency_gthieu_rate = {$_POST['agency_gthieu_rate']} where id = 1";
	$res = $db->voidQuery($sql);
	// echo $sql;
	// var_dump($res);
	if($res)
		header("location:/admin/?r=setting&p=shows");
	else
		echo 'Có lỗi vui lòng thử lại';

}

function CheckList($data)
{
    $uti=new apps_libs_Utilities();
    foreach($data as $item)
    {
        if(!$item["money"]||!is_numeric($item["money"])) return false;
        if(strlen($item["timecreate"])!=7) return false;
        if(!$uti->GetDateMonth($item["timecreate"])) return false;
    }
    return true;
}