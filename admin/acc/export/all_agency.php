<?php
include_once('../../../apps/bootstrap.php');
require('func.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
global $db,$time;$cal_acc;
$cal_acc = new apps_calculate_calculateacc();
$db = new apps_libs_Dbconn();
if(empty($_GET['month']) || $_SESSION['typeAcc'] != 'root'){
	echo('Không Có Dữ Liệu, Tải Lại Sau 3s');
	header('refresh:3;url=/admin/?r=acc&p=list');
	die;	
}
$time = explode('-', $_GET['month']);
$time['month'] = $time[1];
$time['year'] = $time[0];
$sql = "select * from agencies";
$all_agency = $db->query($sql);
// Lấy tổng tiền từ user thuộc đại lý
$i = 1;
foreach($all_agency as $k=>$v){
	$all_agency[$k]->sum = get_agency_sum($v->acc_username);
}
$revenue = $db->query('select * from revenue_share',true);

// Xuât bảng html
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=DS-Daily-{$time['month']}/{$time['year']}.xls"); 
?>
<head>
	<meta charset="utf-8">
</head>
<h2>Doanh Số Đại Lý Tháng <?=$time['month']?> Năm <?=$time['year']?></h2>
<table style="font-size: 16px;">
	<thead>
		<th style="border:1px grey solid">STT</th>
		<th style="border:1px grey solid">Username</th>
		<th style="border:1px grey solid">Doanh Số</th>
		<th style="border:1px grey solid">Tỷ Lệ</th>
		<th style="border:1px grey solid">Hoa Hồng</th>
	</thead>
	<tbody>
<?php foreach($all_agency as $k => $v):?>
	<tr>
		<td style="border:1px grey solid"><?=$i?></td>
		<td style="border:1px grey solid"><?=$v->acc_username?></td>
		<td style="border:1px grey solid"><?=number_format($v->sum)?></td>
		<td style="border:1px grey solid"><?=$revenue->agency_rate?>%</td>
		<td style="border:1px grey solid"><?=number_format($v->sum * $revenue->agency_rate/100)?></td>
	</tr>
<?php $i++; endforeach;?>
</tbody>
</table>

