<?php
set_time_limit(30);
include_once('../../../apps/bootstrap.php');
$rt = new apps_libs_Router();
$user = new apps_libs_UserLogin();
$uti = new apps_libs_Utilities();

if (!$user->CheckAdmin()) $rt->LoginPage();

$i = $rt->GetPost("i");
$time = $rt->GetPost('time');
$time = $uti->GetDateMonth($time);

$calacc = new apps_calculate_calculateacc();
$sta=new apps_calculate_statistics();

$data = null;
$id = $sta->GetUserLocation($i);
$user = $calacc->GetUser($id);
$phonenumber = $sta->GetPhoneNumber($id);
$name = $calacc->GetUserName($id);
$revenue = $sta->GetRevenue($id, $time);
$buya = $sta->GetListBuyA($id, $time);
$getmoneysun = $sta->GetMoneySun($id, $time);
$getmoneybuyaagency = $sta->GetMoneyBuyAAgency($id, $time);

$bank = $sta->GetBank($id);

$data = [
    "user" => $user,
    "name" => $name,
    "phonenumber" => $phonenumber,
    "revenue" => $revenue,
    "buya" => $buya,
    "getmoneysun" => $getmoneysun,
    "getmoneybuyaagency" => $getmoneybuyaagency,
    "bank" => $bank
];

$status=0;
if ($buya > 0 || $getmoneysun > 0 || $getmoneybuyaagency > 0) {
    $status=1;
}
echo json_encode([
    "data"=>$data,
    "status"=>$status
]);

?>