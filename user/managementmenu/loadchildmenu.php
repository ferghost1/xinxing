<?php
include_once('../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckUser()) $rt->LoginPage();
$uti=new apps_libs_Utilities();
$id=$uti->EditDataImportDB($rt->GetPost("id"));

$calacc=new apps_calculate_calculateacc();
$child=$calacc->GetAllChild($user->GetAcc(),4);
$check=true;
if($id==$user->GetAcc()) $check=true;
else $check=$calacc->CheckChild($child,$id);
if($check) 
{
    $data=GetChild($id);
    if($data) 
        echo json_encode($data);
    else echo json_encode("null");
}
else
{
    echo json_encode("null");
}
?>

<?php
function GetChild($id)
{
    $param=[
        "select"=>"children",
        "from"=>"relationshipacc",
        "where"=>"dadacc='".$id."'"
    ];
    $db=new apps_libs_Dbconn();
    $calacc=new apps_calculate_calculateacc();
    $result=$db->Select($param);
    $data=null;
    $i=0;
    while($row=mysqli_fetch_assoc($result))
    {
        $data[$i]["id"]=$row["children"];
        $data[$i]["name"]=$calacc->GetUser($data[$i]["id"]);
        $i++;
    }
    return $data;
}

?>