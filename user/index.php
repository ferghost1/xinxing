<?php 
include('../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
$db = new apps_libs_Dbconn();
if (!$user->CheckUser()) $rt->LoginPage();
//Lấy Đại Lý hoặc Giới Thiệu nếu có
$agency = $db->query("select * from agencies, acc where agencies.acc_username = acc.user and acc.id = '{$_SESSION['userID']}'");
$gioithieu = $db->query("select * from agencies, acc where agencies.gioithieu_username = acc.user and acc.id = '{$_SESSION['userID']}'");

// Lấy tiền tái mua của acc
$acc_repurchase = $db->query("select * from acc where id = '{$_SESSION['userID']}' ");

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
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $rt->GetNameOnline(); ?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="?r=acc&p=chagedetail"><i class="fa fa-fw fa-gear"></i> Tài Khoản</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="/logout.php"><i class="fa fa-fw fa-power-off"></i> Đăng Xuất</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse h-navbar-default">
                <ul class="nav navbar-nav side-nav h-navbar-default h-ul-border-left">
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo1"><i class="glyphicon glyphicon-th-large"></i> Thống Kê Doanh Thu <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo1" class="collapse">
                            <li>
                                <a href="?r=total&p=calacc"><i class="glyphicon glyphicon-check"></i> Thống Kê Doanh Số Cá Nhân</a>
                            </li>
                            <li>
                                <a href="?r=total&p=staintroduce"><i class="glyphicon glyphicon-th"></i> Thống Kê Doanh Số Hệ Thống</a>
                            </li>
                            <li>
                                <a href="?r=total&p=returnagency" ><i class="glyphicon glyphicon-th-list"></i> Thống Kê Doanh Số Tái Tiêu Dùng</a>
                            </li>
                            <?php if($agency):?>
                            <li>
                                <a href="?r=total&p=agency" ><i class="glyphicon glyphicon-th-list"></i> Thống Kê Doanh Số Đại Lý</a>
                            </li>
                            <?php endif;?>
                            <?php if($gioithieu):?>
                            <li>
                                <a href="?r=total&p=gioithieu" ><i class="glyphicon glyphicon-th-list"></i> Hoa Hồng Giới Thiệu Đại Lý</a>
                            </li>
                            <?php endif;?>
                        </ul>
                    </li>
                    <li>
                        <a href="?r=acc&p=relationshipacc"><i class="glyphicon glyphicon-user"></i> Danh Sách Thành Viên</a>
                    </li>
                    <li>
                        <a target="_blank" href="usermanagementmenu.php"><i class="glyphicon glyphicon-align-left"></i> Xem Dạng Cây Thư Mục</a>
                    </li>
                    <li>
                        <a href="?r=acc&p=create"><i class="fa fa-asterisk"></i> Tạo Tài Khoản Con</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">
            <?php
            //echo "<code>".var_dump($page->GetDataDetailAcc())."</code>";
            if ($rt->GetGet('r') && $rt->GetGet('p')) {
                $r = $rt->GetGet('r');
                $p = $rt->GetGet('p');
                $rts=new apps_libs_RouterUser();
                include($rts->GetFile($r, $p));  
            } else {
                include('total/calacc/calacc.php');  
            }
            ?>
        </div>
        <div class="h-footer">
            <span style="font-size:10px">Create by Huu - Copyright: 2018</span>
        </div>
        
        <!-- /#page-wrapper -->
    <script>
        $("#page-wrapper").css("min-height",($(window).height()-40)+"px");
    </script>
    </div>
    <!-- /#wrapper -->
    <!-- Bootstrap Core JavaScript -->
    <script src="../admin/js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="../admin/js/plugins/morris/raphael.min.js"></script>
    <script src="../admin/js/plugins/morris/morris.min.js"></script>
    <script src="../admin/js/plugins/morris/morris-data.js"></script>

</body>

</html>

