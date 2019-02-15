<?php
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckUser()) $rt->LoginPage();
$user=new apps_libs_UserLogin();
$id=$user->GetAcc();

$param = [
    "select" => "acc.user,detailacc.name,detailacc.linkimg",
    "from" => "acc,detailacc",
    "where" => "acc.id='" . $id . "' and detailacc.idacc='" . $id . "'"
];
$db = new apps_libs_Dbconn();
$result_acc = $db->SelectOne($param);
$row_acc = mysqli_fetch_assoc($result_acc);
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
                            <div class="col-xs-9 text-right">
                                <div id="revenue" class="huge">

                                </div>
                                <div>Doanh Số</div>
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
    <div id="returnagency" class="row">
    </div>
    <script>
        $("#select_month").val(get_date_now());
        function load_ajax(time) {
            if (!time) {
                var d = new Date();
                time = d.getFullYear() + "-" + (parseInt(d.getMonth()) + parseInt(1));
            }
            var id=<?php echo "\"".$id."\"" ?>; 
            $("#times").html(time);

            //alert(time);
            $.ajax({
                url: "total/returnagency/loadcalacc.php",
                type: "post",
                dataType: "json",
                data: {
                    id:id,
                    time: time
                },
                success: function (result) {
                    $("#revenue").html(eidtnumbers(result.revenue));
                    $("#moneyshare").html(eidtnumbers(result.moneyshare));
                    $("#share").html(eidtnumbers(result.share));
                    $("#listbuya").html(create_buy(result.listbuya,result.accumulate));
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
                url: "total/returnagency/loadshareagency.php",
                type: "post",
                dataType: "text",
                data: {
                    id:id,
                    time: time
                },
                success: function (result) {
                    $("#returnagency").html(result);
                }
            });
        }
        function select_month()
        {
            var time=$("#select_month").val();
            load_ajax2(time);
            load_ajax(time);
        }
        load_ajax2(get_date_now());
        load_ajax(get_date_now());
    </script>