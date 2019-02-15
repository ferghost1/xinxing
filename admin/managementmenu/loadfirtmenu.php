<?php
include_once('../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();
echo json_encode(GetBigDaddy());
?>

<?php
function GetBigDaddy()
{
    $param=[
        "select"=>"dadacc",
        "from"=>"relationshipacc",
        "where"=>CreateBreakRow()." GROUP BY dadacc" 
    ];
    $db=new apps_libs_Dbconn();
    $calacc=new apps_calculate_calculateacc();
    $result=$db->Select($param);
    $data;
    $i=0;
    while($row=mysqli_fetch_assoc($result))
    {
        $data[$i]["id"]=$row["dadacc"];
        $data[$i]["name"]=$calacc->GetUser($data[$i]["id"]);
        $i++;
    }
    return $data;
}

function CreateBreakRow()
{
    $param=[
        "select"=>"children",
        "from"=>"relationshipacc",
        "where"=>"1"
    ];
    $db=new apps_libs_Dbconn();
    $result=$db->Select($param);
    $where="";
    while($row=mysqli_fetch_assoc($result))
    {
        $where.="dadacc!='".$row["children"]."' and ";
    }
    $where=rtrim($where," and ");
    return $where;
}


?>