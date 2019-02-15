<?php
function get_agency_sum($username){
	global $db,$cal_acc,$time;
	// Tất cả user thuộc đại lý này
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
	      $child = $cal_acc->custom_MoneyAgencyShare($v->id, $time);
	      if(!empty($child)){
	        foreach ($child as $key => $value) {
	          foreach($value as $k1 => $v1){
	            $total += $v1['data']['money']*$total += $v1['data']['money']/100;
	          }
	        }
	      }
	      $data = ['money' => $total, 'username' => $v->user];
	      $result[] = $data;
	      $sum_money += $total;
	  	}
	}
	return $sum_money;
	
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