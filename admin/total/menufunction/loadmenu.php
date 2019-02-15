<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();

$time=$rt->GetPost("time");
if($time)
{
    $time= (new apps_libs_Utilities())->GetDateMonth($time);
    $cal = new apps_calculate_calculate();
    $moneyshare= $cal->CalValueShare([
        "month" => $time["month"],
        "year" => $time["year"]
    ]);
    $total=$cal->CalRevenue([
        "month" => $time["month"],
        "year" => $time["year"]
    ]);
    $share=$cal->CalTotalShare([
        "month" => $time["month"],
        "year" => $time["year"]
    ]);
    $amount_incurred=$cal->AmountIncurred([
        "month" => $time["month"],
        "year" => $time["year"]
    ]);
    $no_amount_incurred=$cal->NoAmountIncurred([
        "month" => $time["month"],
        "year" => $time["year"]
    ]);

    $data=[
        "moneyshare"=>(string)$moneyshare,
        "total"=>(string)$total,
        "share"=>(string)$share,
        "amount_incurred"=>(string)$amount_incurred,
        "no_amount_incurred"=>(string)$no_amount_incurred
    ];

    echo json_encode($data);
}
else 
{
    $data=[
        "moneyshare"=>0,
        "total"=>0,
        "share"=>0,
        "amount_incurred"=>0,
        "no_amount_incurred"=>0
    ];

    echo json_encode($data);
}
?>