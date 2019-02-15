<?php 
include('../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();
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
    <link href="fonts/style.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <script src="js/js.js"></script>
    <script src="js/jquerycookie.js"></script>
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
                <button style="color:black" type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <i class="glyphicon glyphicon-align-justify"></i>
                </button>
                <a class="navbar-brand" href="index.php">Admin</a>
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
                        <a href="?r=total&p=menu"><i class="glyphicon glyphicon-home"></i> Tình Trạng Hệ Thống</a>
                    </li>
                    <li>
                        <a target="_blank" href="usermanagementmenu.php"><i class="glyphicon glyphicon-align-left"></i> Thống Kê Dạng Cây Thư Mục</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo1"><i class="glyphicon glyphicon-th-large"></i> Thống Kê Doanh Thu <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo1" class="collapse">
                            <li>
                                <a href="?r=total&p=show"><i class="glyphicon glyphicon-check"></i> Phát Sinh</a>
                            </li>
                            <li>
                                <a href="?r=total&p=listconstant" ><i class="glyphicon glyphicon-ban-circle"></i> Không Phát Sinh</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo"><span class='icon-users'></span> Quản Lý Tài Khoản <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse">
                            <li>
                                <a href="?r=acc&p=list"><span class='icon-list2'></span> Danh Sách Đại Lý</a>
                            </li>
                            <li>
                                <a href="?r=acc&p=create"><span class='icon-user-plus'></span> Tạo Mới</a>
                            </li>
                            <li>
                                <a href="?r=acc&p=show"><span class='icon-list2'></span> Danh Sách User</a>
                            </li>
                            <li id="listadmin" style="display:none">
                                <a href="?r=acc&p=showadmin"><span class='icon-list2'></span> Danh Sách Admin</a>
                            </li>
                        </ul>
                    </li>
                    <li id="setting" style="display:none">
                        <a href="?r=setting&p=shows"><i class="glyphicon glyphicon-cog"></i> Cài Đặt</a>
                    </li>
                    <li id="settingshare" style="display:none">
                        <a href="?r=setting&p=settingshare"><i class="glyphicon glyphicon-cog"></i> Cài Đặt Đồng Chia Theo Tháng</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">
            <?php
            if ($rt->GetGet('r') && $rt->GetGet('p')) {
                $r = $rt->GetGet('r');
                $p = $rt->GetGet('p');
                include($rt->GetFile($r, $p));  
            } else {
                include('total/menufunction/menu.php');  
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
    <script src="js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="js/plugins/morris/raphael.min.js"></script>
    <script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/morris-data.js"></script>
    <!--DataTable-->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>


    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    
    <link href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css" rel="stylesheet">
    
</body>

</html>

<?php
$user=new apps_libs_UserLogin();
if($user->CheckRoot())
{
    echo "
    <script>
        $(\"#listadmin\").css(\"display\",\"block\");
        $(\"#setting\").css(\"display\",\"block\");
        $(\"#settingshare\").css(\"display\",\"block\");
    </script>
    ";
}

?>

