<?php
include_once('apps/bootstrap.php');
$rt = new apps_libs_Router();
$user = new apps_libs_UserLogin();
if ($user->isOnline()) {
    if ($user->CheckAdmin())
        $rt->AdminPage();
    else $rt->UserPage();
} else {
    if ($rt->GetPost('submit')) {

        if ($user->Login()) {
            if ($user->CheckAdmin())
                $rt->AdminPage();
            else $rt->UserPage();
        }
        else 
        {
            echo "Tài khoản hoặc mật khẩu không đúng";
            die();
        }
    }
}

?>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="admin/fonts/style.css" rel="stylesheet">
    <link href="admin/css/style.css" rel="stylesheet">
    <link href="admin/fonts/style.css" rel="stylesheet">
    <link href="admin/css/style.css" rel="stylesheet">
    <link href="admin/css/bootstrap.min.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="admin/css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="admin/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>

<body>

<div class="h-login panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Đăng Nhập</h3>
    </div>
    <div class="panel-body">
        <form action="" method="post" >
            <div class="form-group">
                <label for="exampleInputEmail1">Tài Khoản</label>
                <input class="form-control" type="text" name="user" placeholder="Nhập tài khoản" />
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Mật Khẩu</label>
                <input class="form-control" type="password" name="pass" placeholder="Nhập mật khẩu" />
            </div>
          
            <input class="btn btn-primary btn-block" type="submit" name="submit" value="Đăng Nhập" />
        </form>
    </div>
</div>
</div>
</body>