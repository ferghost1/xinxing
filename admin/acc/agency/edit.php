<?php
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
$db = new apps_libs_Dbconn();
if (!$user->CheckAdmin()) $rt->LoginPage();
$username = $_GET['user']? $_GET['user']: '';
if(!$username)
	die('Không có dữ liệu user');
$agency = $db->query("select * from agencies where acc_username = '$username'",true);
$list_users = $db->query("select * from agency_relation where agency_username = '$agency->acc_username' ");
// var_dump($)
?>
<div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Thông Tin Đại Lý <?= $agency->acc_username ?></h3>
        </div>
        <div class="panel-body">
        	<div class="edit_container" style="border:solid 1px grey;margin-bottom: 15px;padding: 15px; box-sizing: border-box;border-radius: 30px">
        		<h4>Thông tin đại lý</h4>
        		<div id="notice_add_agency" class="col-md-12"></div>
        		<div class="row">
        			<div class="col-md-4">
        				<label>User Đại Lý</label><br>
        				<input id="agency_user" class="form-control" type="text" value="<?= $agency->acc_username?>" placeholder="Agency Username" disabled>
        			</div>
        			<div class="col-md-4">
        				<label>Khu Vực</label><br>
        				<input id="khuvuc" class="form-control" type="text" value="<?= $agency->khuvuc?>" placeholder="Khu vực">
        			</div>
        			<div class=" col-md-4">
        				<label>User Giới Thiệu</label><br>
        				<input id="nguoigioithieu" class="form-control" type="text" value="<?= $agency->gioithieu_username?>" placeholder="Người Giới Thiệu">
        			</div>
        			<div class=" col-md-4 text-right" style="margin-top:10px;float:right">
        				<button id="submit_edit_agency" class="btn btn-primary" type="button">Sửa</button>
        			</div>
        		</div>
        	</div>
        	<div class="list-container" style="margin-top: 30px">
        		<h4>Add User Cho Đại Lý</h4>
        		<div id="notice_add_user" class="col-md-12"></div>
        		<div class="row">
        			<div class="col-md-5">
        				<label>Username</label><br>
        				<input id="username" class="form-control" type="text" placeholder=" Username">
        			</div>
        			<div class="col-md-2" style="margin-top: 23px">
        				<label style="font-size: 16px">Chuyển đại lý
							<input id="changeAgency" type="checkbox" placeholder=" Username">
        				</label>
        				
        			</div>
        			<div class=" col-md-4 text-left" style="margin-top: 23px">
        				<button id="submit_add_user" class="btn btn-success" type="button">Thêm</button>
        			</div>
        		</div>
        		<h3>List User Thuộc Đại Lý</h3>
				<table id="table-agency" class="table table-bordered">
					<thead>
						<th>Stt</th>
						<th>Username</th>
						<th>Xóa</th>
					</thead>
					<tbody>
					<?php $i = 1; foreach($list_users as $k => $v): ?>
						<tr>
							<td><?= $i ?></td>
							<td><?= $v->username ?></td>
							<td>
								<button class="submit_delete_user btn btn-danger" data-username="<?= $v->username ?>">Xóa</button>
							</td>
						</tr>
					<?php $i++; endforeach; ?>
					</tbody>
				</table>
			</div>
        </div>
    </div>
<script>
$(document).ready(function(){
	$('#submit_add_user').click(function(){
			username = $('#username').val();
			is_change_agency = $('#changeAgency').prop('checked');
		  	$.ajax({
				url: "acc/agency/ajax.php",
		        type: "post",
		        dataType: 'json',
				data: {
			        action: 'ajax_add_user',
			        username: username,
			        agency: '<?= $agency->acc_username ?>',
			        is_change_agency: is_change_agency
		        }
			})
			.done(function(data) {
				if(data[0] == 0){
					$('#notice_add_user').addClass('alert alert-success').removeClass('alert-danger').html(data[1]);
					element = '<tr>';
					element += '<td>new</td>';
					element += '<td>'+username+'</td>';
					element += '<td><button class="submit_delete_user btn btn-danger">Xóa</button></td>';
					element += '</tr>';
					$('#table-agency tbody').prepend(element);
				}
				else{
					$('#notice_add_user').addClass('alert alert-danger').removeClass('alert-success').html(data[1]);
				}
			});
	});
	$('.submit_delete_user').click(function(){
		if(confirm('Có chắc muốn xóa đại lý này')){
			tr_parent = $(this).parents('tr');
			username = $(this).data('username');
			$.ajax({
				url: "acc/agency/ajax.php",
		        type: "post",
		        dataType: 'json',
				data: {
			        action: 'ajax_delete_user',
			        username: username,
		        }
			})
			.done(function(data) {
				if(data[0] == 0){
					$('#notice_add_agency').addClass('alert alert-success').removeClass('alert-danger').html(data[1]);
					tr_parent.fadeOut(700);
				}
				else{
					$('#notice_add_agency').addClass('alert alert-danger').removeClass('alert-success').html(data[1]);
				}
			});
		}	
	});

	$('#submit_edit_agency').click(function(){
		agency_user = $('#agency_user').val();
		khuvuc = $('#khuvuc').val();
		nguoigioithieu  = $('#nguoigioithieu').val();
		  $.ajax({
				url: "acc/agency/ajax.php",
		        type: "post",
		        dataType: 'json',
				data: {
			        action: 'ajax_edit_agency',
			        agency_user: agency_user,
			        khuvuc: khuvuc,
			        nguoigioithieu: nguoigioithieu
		        }
			})
			.done(function(data) {
				if(data[0] == 0){
					$('#notice_add_agency').addClass('alert alert-success').removeClass('alert-danger').html(data[1]);
				}
				else{
					$('#notice_add_agency').addClass('alert alert-danger').removeClass('alert-success').html(data[1]);
				}
			})

			
		});
	});
</script>