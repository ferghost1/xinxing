<?php

include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
global $db;
$db = new apps_libs_Dbconn();
if (!$user->CheckUser()) $rt->LoginPage();
$uti=new apps_libs_Utilities();

$id = $rt->GetPost('id');
$time = $rt->GetPost('time');

if (!$id || !$time) {
    die();
}
$time = (new apps_libs_Utilities())->GetDateMonth($time);
$repurchase_used = repurchase_used($id,$time);
$revenue = GetRevenue($id, $time);
$moneyshare = GetMoneyshare($time);
$share = GetShare($id,$time);
$listbuya=GetListBuyA($id,$time);

$data = [
    "revenue" => $revenue,
    "repurchase_used" => $repurchase_used,
    "moneyshare" => $moneyshare,
    "share" => $share,
    "listbuya"=>$listbuya,
    "accumulate"=>GetAccumulate($time)
];

echo json_encode($data);

function repurchase_used($idacc,$time){
    global $db;
    //Lấy tổng tiền tái tiêu của đơn theo thời gian
    $sql = " select sum(repurchase_money) as sum
        from historyproduct
        where month(timecreate) = '{$time['month']}'
            and year(timecreate) = '{$time['year']}'
            and idacc = '{$idacc}' ";
 
    $used_repurchase = $db->query($sql,true);
    if(empty($used_repurchase->sum))
        return '0';
    return $used_repurchase->sum;
 
}

function GetRevenue($id, $time)
{
    $calacc = new apps_calculate_calculateacc();
    $revenue = $calacc->Revenue($id, $time);
    return $revenue;
}

function GetRevenueAbout($id, $time)
{
    $calacc = new apps_calculate_calculateacc();
    $revenueabout = $calacc->RevenueAbout($id, $time);
    return $revenueabout;
}

function GetMoneyshare($time)
{
    $cal = new apps_calculate_calculate();
    $moneyshare = $cal->CalValueShare($time,TRUE);
    return $moneyshare;
}
function GetShare($id, $time)
{
    $calacc=new apps_calculate_calculateacc();
    return $calacc->Share($id, $time);
}

function GetAccumulate($time)
{
    $cal=new apps_calculate_calculate();
    $apl=$cal->GetApl($time);
    return $apl["accumulate"];
}


function GetListBuyA($id, $time)
{
    $firtbuy=GetFistTimeBuy($id);
    if(!$firtbuy) return [
        "total"=>0,
        "milestones"=>0,
        "paid"=>0,
        "unpaid"=>0
    ];

    $uti=new apps_libs_Utilities();
    $cal=new apps_calculate_calculate();

    $apl=$cal->GetAPL($time);

    $total=0;
    $milestones=0;
    $paidnow=0;
    $paid=0;
    $unpaid=0;

    if(($firtbuy["month"]<=$time["month"]&&$time["year"]==$firtbuy["year"])||$firtbuy["year"]<$time["year"])
    do
    {
        $total+=GetRevenue($id,$firtbuy);
        
        $milestones=GetMoneyReturn($total,$time);
        $number=((int)($total/$apl["accumulate"]));
        $paidnow=GetMoneyshare($firtbuy)*$number;
        $paid+=$paidnow;
        
        $firtbuy=$uti->PlusDateMonth($firtbuy);
    }while(($firtbuy["month"]<=$time["month"]&&$time["year"]==$firtbuy["year"])||$firtbuy["year"]<$time["year"]);

    if($paid>$milestones) $paid=$milestones;
    else $unpaid=$milestones-$paid;

    $data=[
        "total"=>$total,
        "milestones"=>$milestones,
        "paidnow"=>$paidnow,
        "paid"=>$paid,
        "unpaid"=>$unpaid
    ];

    return $data;
}

function GetMoneyReturn($total,$time)
{
    $cal=new apps_calculate_calculate();
    $fln=$cal->GetFLN();
    $apl=$cal->GetAPL($time);
    $number_s=0;
    $number_s=((int)($total/$apl["accumulate"]));
    $data=0;
    for($i=0;$i<$number_s;$i++)
    {
        $percent;
        if($i==0)
            $percent=0;
        else if($i==1)
            $percent=$fln["firstreturnshare"];
        else $percent=(($fln["nextreturnshare"]*($i-1)+$fln["firstreturnshare"])<=$fln["limitreturnshare"])?$fln["firstreturnshare"]+$fln["nextreturnshare"]*($i-1):$fln["limitreturnshare"];
        $data+=$apl["accumulate"]+($apl["accumulate"]*$percent)/100;
    }
    return $data;
}

function GetFistTimeBuy($id)
{
    //SELECT * FROM `historyproduct` WHERE idacc='312452D3DB7588627508CC0187142D' ORDER BY timecreate ASC
    $param=[
        "select"=>"timecreate",
        "from"=>"historyproduct",
        "where"=>"idacc='".$id."' ORDER BY timecreate ASC"
    ];
    $db=new apps_libs_Dbconn();
    $result=$db->SelectOne($param);
    if($result)
    {
        $row=mysqli_fetch_assoc($result);
        $uti=new apps_libs_Utilities();
        return $row["timecreate"]?$uti->GetDateMonthInDataBase($row["timecreate"],true):null;
    }
    else return null;
}
?>