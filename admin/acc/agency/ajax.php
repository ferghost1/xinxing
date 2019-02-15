<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
global $db;
$db = new apps_libs_Dbconn();

if (!$user->CheckAdmin()) $rt->LoginPage(); 
//Chạy hàm động tên action
if(isset($_POST['action']))
	$_POST['action']();
// if(isset($_POST['action']) and $_POST['action'] == 'ajax_add_agency'){
	
// }
function ajax_add_agency(){
	global $db;
	$nguoigioithieu = '';
	if(empty($_POST['agency_user']) || empty($_POST['khuvuc']) ){
		echo json_encode(array(1,'Không đủ dữ liệu'));
		die;
	}

	//Kiểm tra tồn tại của user làm agency
	$res = $db->query("select * from acc where user = '{$_POST['agency_user']}'",true);
	if(empty($res)){
		echo json_encode([1, 'Username Đại lý không tồn tại']);
		exit;
	}
	// Kiếm tra user có làm đại lý chưa
	$res = $db->query("select * from agencies where acc_username = '{$_POST['agency_user']}'",true); 
	if($res){
		echo json_encode([1,'User này đã làm đại lý']);
		die;
	}

	//Kiểm tra tồn tại nguoigioithieu
	if(!empty($_POST['nguoigioithieu'])){
		if($_POST['nguoigioithieu'] == $_POST['agency_user']){
			echo json_encode([1, 'Đại Lý Và Người Giới Thiệu Phải Khác Nhau']);
			die;
		}
		$res = $db->query("select * from acc where user = '{$_POST['nguoigioithieu']}'",true);
		//Nếu ko tồn tại user tương ứng báo lỗi và ngừng lại 
		if(empty($res)){
			echo json_encode([1,'Không tồn tại username người giới thiệu']);
			exit;
		}
		//Kiểm tra người gthieu có phải đại lý k
		$res = $db->query("select * from agencies where acc_username = '{$_POST['nguoigioithieu']}'",true);
		if($res){
			echo json_encode([1,"Người giới thiệu này hiện đang là 1 đại lý "]);
			die;
		}
		//Kiểm tra người gthieu có thuộc đại lý khác chưa
		$res = $db->query("select * from agencies where gioithieu_username = '{$_POST['nguoigioithieu']}'",true);
		if($res){
			echo json_encode([1,"User này đã là người giới thiệu cho đại lý {$res->acc_username}"]);
			die;
		}
		$nguoigioithieu = $_POST['nguoigioithieu'];	
	}

	// Tất cả hơp lệ bắt đầu insert
	$sql = "insert into agencies (acc_username, gioithieu_username, khuvuc) values('{$_POST['agency_user']}', '$nguoigioithieu', '{$_POST['khuvuc']}')";
	$res = $db->voidQuery($sql);
	if($res)
		echo json_encode([0,'Tạo Thành Công']);
	else
		echo json_encode([1,'Có lỗi kiểm tra lại']);
}
function ajax_edit_agency(){
	global $db;
	$nguoigioithieu = '';
	if(empty($_POST['agency_user']) || empty($_POST['khuvuc']) ){
		echo json_encode(array(1,'Không đủ dữ liệu'));
		die;
	}
	//Kiểm tra tồn tại nguoigioithieu
	if(!empty($_POST['nguoigioithieu'])){
		//Ktra đại lý khác người giơi thiệu
		if($_POST['nguoigioithieu'] == $_POST['agency_user']){
			echo json_encode([1, 'Đại Lý Và Người Giới Thiệu Phải Khác Nhau']);
			die;
		}
		//Ktra username tồn tại k
		$res = $db->query("select * from acc where user = '{$_POST['nguoigioithieu']}'",true);
		if(empty($res)){
			echo json_encode([1,'Không tồn tại username người giới thiệu']);
			exit;
		}
		//Kiểm tra người gthieu có phải đại lý k
		$res = $db->query("select * from agencies where acc_username = '{$_POST['nguoigioithieu']}'",true);
		if($res){
			echo json_encode([1,"Người giới thiệu này hiện đang là 1 đại lý "]);
			die;
		}
		//Kiểm tra người gthieu có thuộc đại lý khác chưa
		$res = $db->query("select * from agencies where gioithieu_username = '{$_POST['nguoigioithieu']}'",true);
		if($res){
			if($res->acc_username != $_POST['agency_user']){
				echo json_encode([1,"User này đã là người giới thiệu cho đại lý {$res->acc_username}"]);
				die;
			}
		}
		$nguoigioithieu = $_POST['nguoigioithieu'];	
	}

	// Tất cả hơp lệ bắt đầu insert
	$sql = "insert into agencies (acc_username, gioithieu_username, khuvuc) values('{$_POST['agency_user']}', '$nguoigioithieu', '{$_POST['khuvuc']}')";
	$sql = "update agencies set gioithieu_username = '{$nguoigioithieu}', khuvuc = '{$_POST['khuvuc']}' where acc_username = '{$_POST['agency_user']}'";
	$res = $db->voidQuery($sql);
	if($res)
		echo json_encode([0,'Thay Đổi Thành Công']);
	else
		echo json_encode([1,'Có lỗi kiểm tra lại']);
}
function ajax_delete_agency(){
	global $db;
	$res = $db->voidQuery("delete from agencies where acc_username = '{$_POST['agency_user']}'");
	if($res)
		echo json_encode([0,"Đã xóa user {$_POST['agency_user']} "]);
	else
		echo json_encode([1,"Có lỗi xin kiểm tra lại"]);
}
function ajax_add_user(){
	global $db;
	if(empty($_POST['username']) || empty($_POST['agency'])){
		echo json_encode(array(1,'Không đủ dữ liệu'));
		die;
	}
	//Kiểm tra tồn tại của user
	$res = $db->query("select * from acc where user = '{$_POST['username']}'",true);
	if(empty($res)){
		echo json_encode([1, 'Username không tồn tại']);
		exit;
	}

	// Kiếm tra user có phải đại lý không
	$res = $db->query("select * from agencies where acc_username = '{$_POST['username']}'",true); 
	if($res){
		echo json_encode([1,'User này là một đại lý đại lý']);
		die;
	}
	//Kiểm user này có là người giới thiệu ko
	$res = $db->query("select * from agencies where acc_username = '{$_POST['agency']}' and  gioithieu_username = '{$_POST['username']}'",true); 
	if($res){
		echo json_encode([1,'User này là người giới thiệu ']);
		die;
	}
	//Kiếm tra user có thuộc đại lý nào chưa
	$res = $db->query("select * from agency_relation where username = '{$_POST['username']}'",true); 

	if($res and $_POST['is_change_agency'] == 'false'){
		echo json_encode([1,"User này thuộc về agency {$res->agency_username}"]);
		die;
	}

	// Tất cả hơp lệ bắt đầu insert
	$sql = "insert into agency_relation (username, agency_username) values( '{$_POST['username']}','{$_POST['agency']}') on duplicate key update agency_username = '{$_POST['agency']}'";
	$res = $db->voidQuery($sql);
	if($res)
		echo json_encode([0,'Tạo Thành Công']);
	else
		echo json_encode([1,'Có lỗi kiểm tra lại']);
}
function ajax_delete_user(){
	global $db;
	$res = $db->voidQuery("delete from agency_relation where username = '{$_POST['username']}'");
	if($res)
		echo json_encode([0,"Đã xóa user {$_POST['username']} "]);
	else
		echo json_encode([1,"Có lỗi xin kiểm tra lại"]);
}