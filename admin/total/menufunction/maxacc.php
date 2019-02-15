<?php
include_once('../../../apps/bootstrap.php');
$rt = new apps_libs_Router();
$user = new apps_libs_UserLogin();
if (!$user->CheckAdmin()) die();
$sta=new apps_calculate_statistics();
echo $sta->GetCountNumber();

?>