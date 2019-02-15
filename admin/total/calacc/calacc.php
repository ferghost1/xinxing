<?php
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();
$id = '';
if ($rt->GetGet('id')) {
    $id = $rt->GetGet('id');
}
$param = [
    "select" => "acc.user,detailacc.name,detailacc.linkimg, acc.repurchase_money",
    "from" => "acc,detailacc",
    "where" => "acc.id='" . $id . "' and detailacc.idacc='" . $id . "'"
];
$db = new apps_libs_Dbconn();
$result_acc = $db->SelectOne($param);
$row_acc = mysqli_fetch_assoc($result_acc);
$agency = $db->query("select * from agencies where acc_username = '{$row_acc['user']}' ",true);
$is_agency = $agency? true: false;
$gthieu = $db->query("select * from agencies where gioithieu_username = '{$row_acc['user']}' ",true);
$is_gthieu = $gthieu? true: false;

?>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Thông Tin Tài Khoản</h3>
        </div>
        <div class="panel-body">
            <div class="row alert alert-info alert-dismissable">
                <div class="col-sm-6">
                    <from class="form-horizontal" style="border-bottom: 1px solid #DDDDDD;">
                        <div class="form-group">
                            <div class="row">
                                <label class="control-label col-sm-2"> Tài Khoản:</label>
                                <div class="col-sm-10">
                                    <input disabled style="border:0px" value=<?php echo '"' . $row_acc[ 'user'] . '"' ?> class="form-control" type="text" placeholder="Tên Đăng Nhập" />
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-sm-2">Tên:</label>
                                <div class="col-sm-10">
                                    <input disabled style="border:0px" value=<?php echo '"' . $row_acc[ 'name'] . '"' ?> type="text" class="form-control" placeholder="Tên" />
                                </div>
                            </div><br>
                            <div class="row">
                                <label class="control-label col-sm-2">Tái Tiêu Dùng Còn:</label>
                                <div class="col-sm-10">
                                    <input disabled style="border:0px" value=<?= number_format($row_acc['repurchase_money']) ?> type="text" class="form-control" placeholder="Tái Tiêu Dùng" />
                                </div>
                            </div>
                        </div>
                        </form>
                </div>
                <div class="col-sm-6">
                    <img class="img-thumbnail" style="width:100px;height:80px" src=<?php echo '"'.$rt->GetLinkImg($row_acc['linkimg']).'"' ?> />
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <input value="2018-02" id="select_month" onchange="select_month()" class="form-control" type="month"/>
        </div>
    </div>
    <div style="margin-top:10px;">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <span class="icon-coin-dollar fa-5x fa"></span>
                            </div>
                            <div class="col-xs-9 text-right" style="font-size: 30px">
                                <div id="revenue" class="huge">

                                </div>
                                <div id="repurchase_used" style="font-size: 20px">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <span class="icon-share fa-5x fa"></span>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div id="moneyshare" class="huge">

                                </div>
                                <div>Giá Trị Đồng Chia</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <span class="icon-menu fa-5x fa"></span>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div id="share" class="huge">

                                </div>
                                <div>Tổng Đồng Chia</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
        </div>
    </div>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 id="buyatitle" class="panel-title"><i class="fa fa-calendar-o" style="font-size:20px;"></i> DOANH SỐ CÁ NHÂN</span></h3>
        </div>
        <div class="panel-body">
            <div id="listbuya" class="row">
            </div>
        </div>
    </div>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 id="childtotaltitle" class="panel-title"> DOANH SỐ HỆ THỐNG</h3>
        </div>
        <div id="moneychild" class="panel-body">
        </div>
    </div>
    <?php if($is_agency):?>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 id="agency-title" class="panel-title"> DOANH SỐ ĐẠI LÝ<span style="font-size: 16px"></span></h3>
        </div>
        <div id="child-angency" class=" panel-body">

        </div>
    </div>
    <?php endif;?>
    <?php if($is_gthieu):?>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 id="agency-title" class="panel-title"> Hoa Hồng Giới Thiệu<span style="font-size: 16px"></span></h3>
        </div>
        <div id="child-angency" class=" panel-body">

        </div>
    </div>
    <?php endif;?>
    <div id="returnagency" class="row">
    </div>
    <script>
        $("#select_month").val(get_date_now());
        $("#childtotaltitle").html("DOANH SỐ HỆ THỐNG (Thời Gian "+$("#select_month").val()+")");
        $("#buyatitle").html("DOANH SỐ CÁ NHÂN (Thời Gian "+$("#select_month").val()+")");
        function load_ajax(time) {
            if (!time) {
                var d = new Date();
                time = d.getFullYear() + "-" + (parseInt(d.getMonth()) + parseInt(1));
            }
            var id=<?php echo "\"".$id."\"" ?>; 
            $("#times").html(time);

            //alert(time);
            $.ajax({
                url: "total/calacc/loadcalacc.php",
                type: "post",
                dataType: "json",
                data: {
                    id:id,
                    time: time
                },
                success: function (result) {
                    console.log(result);
                    $("#revenue").html(eidtnumbers(result.revenue));
                    $("#repurchase_used").html('(Tái Tiêu Dùng: '+eidtnumbers(result.repurchase_used)+')');
                    $("#moneyshare").html(eidtnumbers(result.moneyshare));
                    $("#share").html(eidtnumbers(result.share));
                    $("#listbuya").html(create_buy(result.listbuya,result.repurchase_used,result.accumulate));
                }
            });
        }
        function load_ajax1(time) {
            if (!time) {
                var d = new Date();
                time = d.getFullYear() + "-" + (parseInt(d.getMonth()) + parseInt(1));
            }
            var id=<?php echo "\"".$id."\"" ?>; 
            $("#times").html(time);

            $.ajax({
                url: "total/calacc/custom_loadchildtotal.php",
                type: "post",
                dataType: "text",
                data: {
                    id:id,
                    time: time
                },
                success: function (result) {
                    console.log('ajax1'+result)
                    $("#moneychild").html(result);
                }
            });
        }
        function load_ajax2(time) {
            if (!time) {
                var d = new Date();
                time = d.getFullYear() + "-" + (parseInt(d.getMonth()) + parseInt(1));
            }
            var id=<?php echo "\"".$id."\"" ?>; 
            $("#times").html(time);

            $.ajax({
                url: "total/calacc/loadshareagency.php",
                type: "post",
                dataType: "text",
                data: {
                    id:id,
                    time: time
                },
                success: function (result) {
                    console.log('ajax2'+result)
                    $("#returnagency").html(result);
                }
            });
        }
        function load_agency(time){
            if (!time) {
                var d = new Date();
                time = d.getFullYear() + "-" + (parseInt(d.getMonth()) + parseInt(1));
            }

            $("#times").html(time);

            $.ajax({
                url: "total/calacc/load_agency.php",
                type: "post",
                dataType: "text",
                data: {
                    username: '<?= $row_acc["user"]?>',
                    time: time
                },
                success: function (result) {
                    $('#agency-title span').html(' (Thời Gian: '+time+')');
                    $("#child-angency").html(result);
                    $('.datatable').DataTable( {
                        dom: 'Bfrtip',
                        className:'btn btn-primary',
                        buttons: [
                            'excel',
                        ]
                    } );
                }
            });
        }
        function load_gthieu_agency(time){
            if (!time) {
                var d = new Date();
                time = d.getFullYear() + "-" + (parseInt(d.getMonth()) + parseInt(1));
            }

            $("#times").html(time);

            $.ajax({
                url: "total/calacc/load_gthieu.php",
                type: "post",
                dataType: "text",
                data: {
                    username: '<?= $row_acc["user"]?>',
                    time: time
                },
                success: function (result) {
                    $('#agency-title span').html(' (Thời Gian: '+time+')');
                    $("#child-angency").html(result);
                    $('.datatable').DataTable( {
                        dom: 'Bfrtip',
                        className:'btn btn-primary',
                        buttons: [
                            'excel',
                        ]
                    } );
                }
            });
        }
        function create_buy(list,repurchase_used,accumulate)
        {
            if(!list) return "<span style=\"color:red\">Bạn không đủ điều kiện</span>";
            var html='';
            html+="<div class=\"alert alert-info\">";
            html+="Số Tiền Đã Mua Hàng: "+eidtnumbers(list.total)+"<br/>"; 
            html+="<b>Tiền Đồng Chia Trong Tháng: "+eidtnumbers(list.paidnow)+"</b><br/>";
            html+="<b>Tiền Tái Tiêu Dùng Sử Dụng Trong Tháng: "+eidtnumbers(repurchase_used)+"</b><br/>";
            html+="Tổng Số Tiền Đã Trả Là: "+eidtnumbers(list.paid)+"<br/>"; 
            html+="Số Tiền Còn Lại Chưa Trả Là: "+eidtnumbers(list.unpaid)+"<br/>";               
            html+="</div>";
            return html;
            
        }
        function select_month()
        {
            var time=$("#select_month").val();
            $("#childtotaltitle").html("DOANH SỐ HỆ THỐNG (Thời Gian "+$("#select_month").val()+")");
            $("#buyatitle").html("DOANH SỐ CÁ NHÂN (Thời Gian "+$("#select_month").val()+")");
        
            load_ajax(time);
            load_ajax1(time);
            load_ajax2(time);
            <?php if($is_agency):?>
            load_agency(time);
            <?php endif;?>
            <?php if($is_gthieu):?>
                 load_gthieu_agency(time);
            <?php endif;?>
           
        }
        load_ajax(get_date_now());
        load_ajax1(get_date_now());
        load_ajax2(get_date_now());
        <?php if($is_agency):?>
            load_agency(time);
            <?php endif;?>
            <?php if($is_gthieu):?>
                 load_gthieu_agency(time);
            <?php endif;?>
    </script>

