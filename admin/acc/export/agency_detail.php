<?php
include_once('/apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
global $db,$time;

$db = new apps_libs_Dbconn();
if(empty($_GET['month']))
	die('Không Có Dữ Liệu');
$time = explode(',', $_GET['month']);
var_dump($db);
var_dump($time);