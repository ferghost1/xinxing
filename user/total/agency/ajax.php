<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
global $db;
$db = new apps_libs_Dbconn();

if (!$user->CheckUser()) 
	die;
//Chạy hàm động tên action
if(isset($_POST['action']))
	$_POST['action']();
function nguoigioithieu_money(){
	//Trả về tổng giá trị của đại lý
	echo agency_users_money(true);
}
function agency_users_money($getSum = false){
	global $db;
	$username = $_POST['username'];
	$time = $_POST['time'];
	if (!$username || !$time) {
	    die();
	}
	$time = (new apps_libs_Utilities())->GetDateMonth($time);
	$calacc = new apps_calculate_calculateacc();

	// Lấy tất cả user thuộc agency
	$agency_users = $db->query("select acc.user, acc.id from acc, agency_relation where acc.user = agency_relation.username and agency_relation.agency_username = '{$username}'");
	//Tổng tiền từ các hệ thống
	global $sum_money;
	$sum_money = 0;
	$result = array();
	// Lấy tất cả hệ thống của users
	if($agency_users){
	  foreach($agency_users as $k => $v){
	      //Lấy tổng tiền hóa đơn 
	      $total = get_money($v->id,$time);
	      //Lấy tổng tiền hệ thống 
	      $child = $calacc->custom_MoneyAgencyShare($v->id, $time);
	      if(!empty($child)){
	        foreach ($child as $key => $value) {
	          foreach($value as $k1 => $v1){
	            $total += $v1['data']['money'];
	          }
	        }
	      }
	      $data = ['money' => $total, 'username' => $v->user];
	      $result[] = $data;
	      $sum_money += $total;
	  }
}

if($getSum){
	$sum = 0;
	foreach($result as $v){
		$sum += $v['money']; 
	}
	return $sum;
}
if ($result && !$getSum) {
    echo create_agency_table($result);
}
}
function create_agency_table($data){
  global $sum_money;
  $uti=new apps_libs_Utilities();
    ?>
    <div class="col-md-12" style="margin-bottom: 30px">
      <span style="font-size: 18px">
        Tổng tiền: <bold style="color:red;font-size:18px"><?= $uti->EditNumber($sum_money) ?></bold><br>
      </span>
      <span style="font-size: 18px">
        Tổng hoa hồng: <bold style="color:red;font-size: 18px"><?= $uti->EditNumber($sum_money*0.03) ?></bold>
      </span>
       
    </div>
    <table class="table table-bordered">
      <tr>
        <th>STT</th>
        <th>Username</th>
        <th>Doanh Số Phát Sinh</th>
        <th>Tỷ Lệ Chiết Khấu</th>
        <th>Số Tiền Được Nhận</th>
      </tr>
      <?php $i = 1;
       foreach($data as $k => $v):
          // Loại ra child nào ko phát sinh đon hàng
          if($v['money'] == 0)
            continue;
        ?>
          <tr>
            <td><?= $i ?></td>
            <td><?= $v['username'] ?></td>
            <td><?= $uti->EditNumber($v['money']) ?></td>
            <td>3%</td>
            <td><?= $uti->EditNumber($v['money']*0.03)?></td>
          </tr>
        <?php  
       $i++; endforeach;?>
    </table>
    <?php
}
function get_money($id,$time){
  global $db;
  $sql = "select * from historyproduct, listproduct where historyproduct.id = listproduct.idhp and historyproduct.idacc = '{$id}' and month(timecreate) = '{$time['month']}' and year(timecreate) = '{$time['year']}'";
  $orders = $db->query($sql);
  $total = 0;
  if($orders){
    foreach($orders as $k => $v){
      $total += $v->quantity*$v->price;
    }
  }
  return $total;
}

