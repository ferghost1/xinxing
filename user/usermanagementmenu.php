<?php 
include('../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckUser()) $rt->LoginPage();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin</title>
    <!-- Bootstrap Core CSS -->
    <link href="../admin/fonts/style.css" rel="stylesheet">
    <link href="../admin/css/style.css" rel="stylesheet">
    <link href="../admin/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../admin/css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../admin/css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../admin/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- jQuery -->
    <script src="../admin/js/jquery.js"></script>
    <script src="../admin/js/js.js"></script>
    <script src="../admin/js/jquerycookie.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav style="background-color:#fff" class="navbar navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <i class="glyphicon glyphicon-align-justify"></i>
                </button>
                <a class="navbar-brand" href="index.php">Xin Xing</a>
            </div <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user"></i>
                        <?php echo $rt->GetNameOnline(); ?>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="?r=acc&p=chagedetail">
                                <i class="fa fa-fw fa-gear"></i> Tài Khoản</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="http://member.xinxing.com.vn/logout.php">
                                <i class="fa fa-fw fa-power-off"></i> Đăng Xuất</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse h-navbar-default">
                <ul class="nav navbar-nav side-nav h-edit-size h-navbar-default h-ul-border-left">
                    <ul id="h-menu" class="h-ul" style="display:block;margin-left:0px;">
                    </ul>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">
        <?php
        ?>
            <input id="id" type="text" style="display:none"/>
            <div style="margin:0 auto;display:none;" id="plwmenu">
                <img style="margin-left:50%" src="../admin/img/pleasewait/plw.gif" />
            </div>
            <div id="pleasechose">
                <div class="jumbotron">
                    <h1>Quản lý user dạng cây thư mục</h1>
                    <p>
                        Đây là chức năng quảy lý user dạng cây thư mục. Vui lòng chọn tài khoản cần
                        thống kê trên menu.
                    </p>
                </div>
            </div>
            <div id="show" style="display:none">               
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
                                                <input id="user" disabled style="border:0px" value="" class="form-control" type="text" placeholder="Tên Đăng Nhập" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="control-label col-sm-2">Tên:</label>
                                            <div class="col-sm-10">
                                                <input id="nameuser" disabled style="border:0px" value="" type="text" class="form-control" placeholder="Tên" />
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                            </div>
                            <div class="col-sm-6">
                                <img id="imguser" class="img-thumbnail" style="width:100px;height:80px" src="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <input value="2018-02" id="select_month" onchange="select_month()" class="form-control" type="month" />
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
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 id="buyatitle" class="panel-title">
                            <i class="fa fa-calendar-o" style="font-size:20px;"></i> DOANH SỐ CÁ NHÂN</span>
                        </h3>
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
                <div id="returnagency" class="row">
                </div>
            </div>
        </div>
        <div class="h-footer">
            <span style="font-size:10px">Create by Huu - Copyright: 2018</span>
        </div>

        <!-- /#page-wrapper -->
        <script>
            $("#page-wrapper").css("min-height", ($(window).height() - 40) + "px");
        </script>
    </div>
    <!-- /#wrapper -->
    <!-- Bootstrap Core JavaScript -->
    <script src="../admin/js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="../admin/js/plugins/morris/raphael.min.js"></script>
    <script src="../admin/js/plugins/morris/morris.min.js"></script>
    <script src="../admin/js/plugins/morris/morris-data.js"></script>
    <script>
        $("#select_month").val(get_date_now());
        function load_acc(id) {
            $.ajax({
                url: "managementmenu/loadacc.php",
                type: "post",
                dataType: "json",
                data: {
                    id:id
                },
                success: function (result) {
                    $("#user").val(result.user);
                    $("#nameuser").val(result.nameuser);
                    $("#imguser").attr('src',"../"+result.imguser);
                }
            });
        }
        function load_ajax(time,id) {
            if (!time) {
                var d = new Date();
                time = d.getFullYear() + "-" + (parseInt(d.getMonth()) + parseInt(1));
            }
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
                    $("#revenue").html(eidtnumbers(result.revenue));
                    $("#moneyshare").html(eidtnumbers(result.moneyshare));
                    $("#share").html(eidtnumbers(result.share));
                    $("#listbuya").html(create_buy(result.listbuya,result.accumulate));
                }
            });
        }
        function load_ajax1(time,id) {
            if (!time) {
                var d = new Date();
                time = d.getFullYear() + "-" + (parseInt(d.getMonth()) + parseInt(1));
            }
            $("#times").html(time);

            $.ajax({
                url: "total/staintroduce/custom_loadchildtotal.php",
                type: "post",
                dataType: "text",
                data: {
                    id:id,
                    time: time
                },
                success: function (result) {
                    $("#moneychild").html(result);
                }
            });
        }
        function load_ajax2(time,id) {
            if (!time) {
                var d = new Date();
                time = d.getFullYear() + "-" + (parseInt(d.getMonth()) + parseInt(1));
            }
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
        function create_buy(list,accumulate)
        {
            if(!list) return "<span style=\"color:red\">Bạn không đủ điều kiện</span>";
            var html='';
            html+="<div class=\"alert alert-info\">";
            html+="Số Tiền Đã Mua Hàng: "+eidtnumbers(list.total)+"<br/>"; 
            html+="<b>Tiền Đồng Chia Trong Tháng: "+eidtnumbers(list.paidnow)+"</b><br/>";
            html+="Tổng Số Tiền Đã Trả Là: "+eidtnumbers(list.paid)+"<br/>"; 
            html+="Số Tiền Còn Lại Chưa Trả Là: "+eidtnumbers(list.unpaid)+"<br/>";              
            html+="</div>";
            return html;
        }
        function select_month()
        {
            var time=$("#select_month").val();
            load_ajax(time,$("#id").val());
            load_ajax1(time,$("#id").val());
            load_ajax2(time,$("#id").val());
            $("#childtotaltitle").html("DOANH SỐ HỆ THỐNG (Thời Gian "+$("#select_month").val()+")");
            $("#buyatitle").html("DOANH SỐ CÁ NHÂN (Thời Gian "+$("#select_month").val()+")");
        }
    </script>
    <script>
        function load_firt_menu() {
            $("#h-menu").html('<img class="h-button-d" style="height:60px;width:70px;" src="../admin/img/pleasewait/plwmenu.gif" />');
            $.ajax({
                url: "managementmenu/loadfirtmenu.php",
                type: "post",
                dataType: "json",
                data: {
                },
                success: function (result) {
                    //alert(result);
                    //alert(create_menu(result));
                    $('#h-menu').html(create_menu(result));
                    $('#h-menu').slideUp(50, 'swing').fadeIn(200);
                }
            });
        }
        function load_child_menu(id) {
            $.ajax({
                url: "managementmenu/loadchildmenu.php",
                type: "post",
                dataType: "json",
                data: {
                    id: id
                },
                success: function (result) {
                    //alert(result);
                    //alert(create_menu(result));
                    $("#ul" + id).html(create_menu(result));
                    $("#ul" + id).slideUp(50, 'swing').fadeIn(200);
                }
            });
        }
        function create_menu(data) {
            if (data == "null") return "";
            var html = '';
            for (var i = 0; i < data.length; i++) {
                html += "<li class=\"h-li\">";
                html += "<button onclick=\"h_button_d_click('" + data[i].id + "')\" class=\"h-button-d\"><i class=\"fa fa-user\"></i> " + data[i].name + "</button>";
                html += "<button onclick=\"h_show_ul('" + data[i].id + "')\" class=\"h-button-s\"><i class=\"fa fa-fw fa-caret-down\"></i></button>";
                html += "<ul id=\"ul" + data[i].id + "\" class=\"h-ul\"></ul>";
                html += "</li>";
            }
            if (html == '') return "Không có thành viên";
            return html;
        }
        function h_show_ul(id) {
            var css = $("#ul" + id).css("display");
            if (css == "none") {
                $("#ul" + id).css("display", "block");
                $("#ul" + id).html('<img class="h-button-d" style="height:60px;width:70px;" src="../admin/img/pleasewait/plwmenu.gif" />');
                setTimeout(load_child_menu(id), 10);
            } else {
                $("#ul" + id).css("display", "none");
            }
        }
        function h_button_d_click(id) {
            $("#plwmenu").css("display","block");
            $("#pleasechose").css("display","none");
            $("#show").css("display","block");
            $("#id").val(id);
            load_ajax($("#select_month").val(),$("#id").val());
            load_ajax1($("#select_month").val(),$("#id").val());
            load_ajax2($("#select_month").val(),$("#id").val());
            load_acc(id);

            $("#show").slideUp(50, 'swing').fadeIn(200);
            $("#plwmenu").css("display","none");
        }
        load_firt_menu();
    </script>
</body>

</html>