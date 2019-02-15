<?php
set_time_limit(600);
include_once('../../../apps/bootstrap.php');
$rt = new apps_libs_Router();
$user = new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();
$uti = new apps_libs_Utilities();

include_once('PHPExcel/PhpExcel.php');

$time = $rt->GetPost('time');
$time = $uti->GetDateMonth($time);

$data=$rt->GetPost("data");
$data=json_decode($data,true);
if ($time == "" || !$time) {
    echo "Lỗi";
    die();
}

$filename = "Thong-Ke-Thang-" . $time["month"] . "-nam-" . $time["year"] . ".xlsx";
if (file_exists($filename)) unlink($filename);
$excel = new WriteExcel($filename);
$excel->SaveExcel($data);
echo "Tạo xong!   <a style=\"font-size:20px;\" target=\"_blank\" href=\"total/menufunction/dowloadfile.php?file=" . $filename . "\">Tải xuống</a>";