<?php
include_once('../../../apps/bootstrap.php');
$rt = new apps_libs_Router();
$user = new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();

$filename=$rt->GetGet("file");
if(!$filename||$filename=="") die();
if(!file_exists($filename)) die();

$fp = fopen($filename, "rb");
header("Content-type: application/octet-stream");
header("Content-length: " . filesize($filename));
header('Content-disposition: attachment; filename="'.$filename.'"');  
fpassthru($fp);
fclose($fp);

?>