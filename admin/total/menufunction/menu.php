<?php
$rt = new apps_libs_Router();
$user = new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();

$cal = new apps_calculate_calculate();
?>
<div class="row">
    <ol class="breadcrumb">
        <li class="active" style="font-size:20px;">
            <i class="fa fa-calendar-o" style="font-size:20px;"></i> Thống kê
            <span style="font-size:20px;" id="times"></span>
        </li>
    </ol>
</div>
<div class="row">
    <form class="form-horizontal">
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Chọn Khoảng Thời Gian:</label>
            <div class="col-sm-5">
                <input class="form-control" value="2018-02" id="select_months" onchange="select_month()" type="month" />
            </div>
        </div>
    </form>
</div>
<div class="row">
    <div class="col-lg-5">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <span class="icon-coin-dollar fa-5x fa"></span>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div id="total" class="huge">

                        </div>
                        <div>Tổng Doanh Số</div>
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
<div class="row">
    <div class="col-lg-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa-smile-o fa-5x fa"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div id="amount_incurred" class="huge">

                        </div>
                        <div>Số Lượng Tài Khoản Phát Sinh</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa-frown-o fa-5x fa"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div id="no_amount_incurred" class="huge">

                        </div>
                        <div>Số Lượng Tài Khoản Không Phát Sinh</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-3">
        <input id="excel_s" type="button" class="btn btn-lg btn-success" onclick="getmaxacc()" value="Xuất Doanh Thu File Excel" />
    </div>
    <div class="col-lg-6">
        <div style="font-size:20px;" id="excel_show">
        </div>
    </div>
    <div class="col-lg-3">
        <input id="showshare" type="button" class="btn btn-lg btn-success" onclick="check_date()" value=<?php echo "'" . (($cal->CheckShowShare()) ? ("Ngừng Xuất Doanh Thu") : ("Xuất Doanh Thu")) . "'"; ?> />
    </div>
</div>
<script>
    $("#select_months").val(get_date_now());
    function excel() {
        $.ajax({
            url: "total/menufunction/createexcel.php",
            type: "post",
            dataType: "text",
            timeout: 600000,
            data: {
                time: $("#select_months").val(),
                data: JSON.stringify(data)
            },
            success: function (result) {
                messexcel(result);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(thrownError);
                messexcel("<span style='color:red'>Có lỗi khi tạo file excel<span>");
            }
        });
    }

    var max = 0;
    var dataexcel = null;
    var index=0;

    function getdataexcel(i) {
        if (i > max) {
            excel();
            return;
        }
        $.ajax({
            url: "total/menufunction/getdataexcel.php",
            type: "post",
            dataType: "json",
            timeout: 30000,
            data: {
                i: i,
                time: $("#select_months").val()
            },
            success: function (result) {
                if (result.status==1)
                {
                    data[index]=new Object();
                    data[index]=result.data;
                    index++;
                    console.log(result);
                }    
                $("#excel_show").html("Tài Khoản: <span style='color:green;font-size:20px'>"+result.data.user +"</span> (" + i + "/" + max+")");
                setTimeout(function () {
                    getdataexcel(i + 1);
                }, 1000);

            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(thrownError);
                messexcel("<span style='color:red'>Có lỗi khi lấy dữ liệu<span>");
            }
        });
    }
    function getmaxacc() {
        max = 0;
        index=0;
        data = new Array();
        $("#excel_s").val("Vui lòng đợi...");
        $("#excel_s").attr('disabled', true);
        $("#select_months").attr('disabled', true);
        $.ajax({
            url: "total/menufunction/maxacc.php",
            type: "post",
            dataType: "text",
            timeout: 30000,
            data: {
            },
            success: function (result) {
                max = parseInt(result);
                getdataexcel(0);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                messexcel("<span style='color:red'>Có lỗi khi kết nối tới server<span>");
            }
        });
    }
    function messexcel(result) {
        $("#excel_show").html("");
        $("#excel_show").html(result);
        $("#excel_s").val("Xuất Doanh Thu File Excel");
        $('#excel_s').removeAttr('disabled');
        $('#select_months').removeAttr('disabled');
    }


    function check_date() {
        $("#showshare").val("Vui lòng đợi...");
        $("#showshare").attr('disabled', true);
        $.ajax({
            url: "total/menufunction/settingshowshare.php",
            type: "post",
            dataType: "text",
            data: {
            },
            success: function (result) {
                $("#showshare").val(result);
                $('#showshare').removeAttr('disabled');
            }
        });
    }

    function load_ajax(time) {
        if (!time) {
            var d = new Date();
            time = d.getFullYear() + "-" + (parseInt(d.getMonth()) + parseInt(1));
        }
        $("#times").html(time);
        //alert(time);
        $.ajax({
            url: "total/menufunction/loadmenu.php",
            type: "post",
            dataType: "json",
            data: {
                time: time
            },
            success: function (result) {
                //alert(result);
                //return;
                $("#moneyshare").html(eidtnumbers(result.moneyshare));
                $("#total").html(eidtnumbers(result.total));
                $("#share").html(eidtnumbers(result.share));
                $("#amount_incurred").html(eidtnumbers(result.amount_incurred));
                $("#no_amount_incurred").html(eidtnumbers(result.no_amount_incurred));
            }
        });
    }
    function select_month() {
        if ($("#select_months").val() == get_date_now()) {
            $("#showshare").css("display", "block");
        } else $("#showshare").css("display", "none");

        $("#excel_show").html("");

        var time = $("#select_months").val();
        $("#moneyshare").html(0);
        $("#total").html(0);
        $("#share").html(0);
        $("#amount_incurred").html(0);
        $("#no_amount_incurred").html(0);
        load_ajax(time);
    }

    load_ajax(get_date_now());
</script>