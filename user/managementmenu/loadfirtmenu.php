<?php
include_once('../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckUser()) $rt->LoginPage();
echo json_encode(GetBigDaddy());
?>

<?php
function GetBigDaddy()
{
    $user=new apps_libs_UserLogin();
    $calacc=new apps_calculate_calculateacc();
    
    $id=$user->GetAcc();
    $name=$calacc->GetUser($id);
    $data[0]["id"]=$id;
    $data[0]["name"]=$name;
    return $data;
}
?>