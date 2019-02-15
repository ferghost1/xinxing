<?php
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
$db = new apps_libs_Dbconn();
if (!$user->CheckAdmin()) $rt->LoginPage();
//Lấy tất cả agency
$list_agency = $db->query('select * from agencies, acc where acc.user = agencies.acc_username');
?>  
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Danh Sách Đại Lý</h3>
        </div>
        <div class="panel-body">
        	<div class="add_container" style="border:solid 1px grey;margin-bottom: 15px;padding: 15px; box-sizing: border-box;">
        		<h4>Thêm Đại Lý</h4>
        		<div id="notice_add_agency" class="col-md-12"></div>
        		<div class="row">
        			<div class="col-md-4">
        				<label>User Đại Lý</label><br>
        				<input id="agency_user" class="form-control" type="text" name="username" placeholder="Agency Username">
        			</div>
        			<div class="col-md-4">
        				<label>Khu Vực</label><br>
        				<input id="khuvuc" class="form-control" type="text" name="khuvuc" placeholder="Khu vực">
        			</div>
        			<div class=" col-md-4">
        				<label>User Giới Thiệu</label><br>
        				<input id="nguoigioithieu" class="form-control" type="text" name="nguoigioithieu" placeholder="Người Giới Thiệu">
        			</div>
        			<div class=" col-md-4 text-right" style="margin-top:10px;float:right">
        				<button id="submit_add_agency" class="btn btn-primary" name="submit_add" type="button">Thêm</button>
        			</div>
        		</div>
        		<div class="row float-left">
        				<form method="get" action="acc/export/all_agency.php" target="_blank">
        					<label>Tháng</label>
        					<input type="month" name="month">
        					<button class="btn btn-primary">Xuất Excel</button>
        				</form>
        		</div>
        	</div>
        	<div class="list-container">
			<table id="table-agency" class="table table-bordered">
				<thead>
					<th>Stt</th>
					<th>Username</th>
					<th>Khu Vực</th>
					<th>Người Giới Thiệu</th>
					<th>Xóa/Sửa - Thêm User</th>
				</thead>
				<tbody>
					<?php $i = 1; foreach($list_agency as $k => $v): ?>
						<tr>
							<td><?= $i ?></td>
							<td><a href="?r=total&p=calacc&id=<?=$v->id?>"><?= $v->acc_username ?></a></td>
							<td><?= $v->khuvuc ?></td>
							<td><?= $v->gioithieu_username ?></td>
							<td>
								<button class="btn btn-primary">
									<a href="?r=acc&p=edit&user=<?=$v->acc_username?>" style="color: white">Sửa</a>
								</button>
								<button class=" delete_agency btn btn-danger" type="button" data-user="<?= $v->acc_username ?>">
									Xóa
								</button>
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
	$('.delete_agency').click(function(){
		if(confirm('Có chắc muốn xóa đại lý này')){
			tr_parent = $(this).parents('tr');
			agency_user = $(this).data('user');
		  	$.ajax({
				url: "acc/agency/ajax.php",
		        type: "post",
		        dataType: 'json',
				data: {
			        action: 'ajax_delete_agency',
			        agency_user: agency_user,
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

	$('#submit_add_agency').click(function(){
		agency_user = $('#agency_user').val();
		khuvuc = $('#khuvuc').val();
		nguoigioithieu  = $('#nguoigioithieu').val();
		  $.ajax({
				url: "acc/agency/ajax.php",
		        type: "post",
		        dataType: 'json',
				data: {
			        action: 'ajax_add_agency',
			        agency_user: agency_user,
			        khuvuc: khuvuc,
			        nguoigioithieu: nguoigioithieu
		        }
			})
			.done(function(data) {
				if(data[0] == 0){
					$('#notice_add_agency').addClass('alert alert-success').removeClass('alert-danger').html(data[1]);
					element = '<tr>';
					element += '<td>new</td>';
					element += '<td>'+agency_user+'</td>';
					element += '<td>'+khuvuc+'</td>';
					element += '<td>'+nguoigioithieu+'</td>';
					element += '<td>updating</td>';
					element += '</tr>';
					$('#table-agency tbody').prepend(element);
				}
				else{
					$('#notice_add_agency').addClass('alert alert-danger').removeClass('alert-success').html(data[1]);
				}
			})

			
		});
	});
</script>