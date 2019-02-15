<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckRoot()) $rt->LoginPage();
$time=$rt->GetPost("time");
if(!$time)
{
    die();
}

$param=[
    "select"=>"limitshare,timecreate",
    "from"=>"settingshare",
    "where"=>"year(timecreate)=".$time
];
$db=new apps_libs_Dbconn();
$result=$db->Select($param);

$data=null;
$i=0;
while($row=mysqli_fetch_assoc($result)) 
{
    $data[$i]["money"]=$row["limitshare"];
    $data[$i]["timecreate"]=EditTime($row["timecreate"]);
    $i++;
}
echo json_encode($data);
?>

<?php
function EditTime($time)
{
    $time=explode(" ",$time)[0];
    $time=explode("-",$time);
    $time=$time[0]."-".$time[1];
    return $time;
}
?>