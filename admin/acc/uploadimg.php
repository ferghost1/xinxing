<?php
include_once('../../apps/bootstrap.php');
$rt = new apps_libs_Router();
$user = new apps_libs_UserLogin();
if (!$user->isOnline()) $rt->LoginPage();
if (isset($_FILES['file'])) {
    $duoi = explode('.', $_FILES['file']['name']); // tách chuỗi khi gặp dấu .
    $duoi = $duoi[(count($duoi) - 1)];//lấy ra đuôi file

    $img = '/img/rec/' . rand(1, 100000) . '.' . $duoi;
    if (move_uploaded_file($_FILES['file']['tmp_name'], '..' . $img)) {
        echo 'admin' . $img;
    } else {
        echo "";
    }
} else {
    echo "";
}
?>