<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
global $db,$time,$username,$revenue,$agency_username;
$db = new apps_libs_Dbconn();
if (!$user->CheckAdmin()) $rt->LoginPage();

$username = $rt->GetPost('username');
$time = $rt->GetPost('time');
if (!$username || !$time) {
    die();
}
$time = (new apps_libs_Utilities())->GetDateMonth($time);
$calacc = new apps_calculate_calculateacc();
// Lấy tất cả user thuộc agency
$agency_username = $db->query("select * from agencies where gioithieu_username = '{$username}'",true)->acc_username;
$agency_users = $db->query("select acc.user, acc.id from acc, agency_relation where acc.user = agency_relation.username and agency_relation.agency_username = '{$agency_username}'");
$revenue = $db->query("select * from revenue_share where id = 1",true);

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

if ($result) {
    echo create_agency_table($result);
}
else echo "<span style=\"color:red\">Không có dữ liệu</span>";
function create_agency_table($data){
  global $sum_money;
  global $db,$time,$username,$revenue,$agency_username;
  $uti=new apps_libs_Utilities();
    ?>
    <div class="col-md-12" style="margin-bottom: 30px">
      <span style="font-size: 18px">
        Người Giới Thiệu: <bold style="color:red;font-size: 18px"><?=$username ?></bold>
      </span><br>
      <span style="font-size: 18px">
        Tổng tiền: <bold style="color:red;font-size:18px"><?= $uti->EditNumber($sum_money) ?></bold><br>
      </span>
      <span style="font-size: 18px">
        Tổng hoa hồng: <bold style="color:red;font-size: 18px"><?= number_format($sum_money*$revenue->agency_gthieu_rate/100) ?></bold>
      </span>
       
    </div>
    <table class="table table-bordered datatable">
      <thead>
        <th>STT</th>
        <th>Username</th>
        <th>Doanh Số Phát Sinh</th>
        <th>Tỷ Lệ Chiết Khấu</th>
        <th>Số Tiền Được Nhận</th>
        <th>Đại Lý</th>
        <th>Giới Thiệu</th>
        <th>Thời Gian</th>
      </thead>
      <tbody>
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
            <td><?=$revenue->agency_gthieu_rate?>%</td>
            <td><?= number_format($v['money']*$revenue->agency_gthieu_rate/100)?></td>
            <td><?=$agency_username?></td>
            <td><?=$username?></td>
            <td><?= $time['month'].'/'.$time['year']?></td>
          </tr>
        <?php  
       $i++; endforeach;?>
     </tbody>
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

?>