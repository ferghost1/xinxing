<?php
include_once('../../apps/bootstrap.php');
if (!(new apps_libs_UserLogin())->isOnline()) (new apps_libs_Router())->LoginPage();
if (isset($_POST) && isset($_FILES['file'])) {
    $duoi = explode('.', $_FILES['file']['name']); // tách chuỗi khi gặp dấu .
    $duoi = $duoi[(count($duoi) - 1)];//lấy ra đuôi file
    if ($duoi == 'jpg' || $duoi == 'png' || $duoi == 'gif') {
        $img = '/img/rec/' . rand(1,100000) . '.'.$duoi;
        if (move_uploaded_file($_FILES['file']['tmp_name'], '../../admin'.$img)) {
            echo 'admin'.$img;
        } else {
            echo "";
        }
    } else { //nếu k phải file ảnh
        echo "";
    }
} else {
    echo "";
}
?>