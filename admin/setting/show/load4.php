<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckRoot()) $rt->LoginPage();

$db=new apps_libs_Dbconn();

$param=[
    "select"=>"extractmax",
    "from"=>"setting",
    "where"=>"id=1"
];
$result=$db->SelectOne($param);
if($result)
{
    $row=mysqli_fetch_assoc($result);
    $data=[
        "extractmax"=>$row["extractmax"],
        "list"=>[]
    ];
    $param2=[
        "select"=>"money,percentsend",
        "from"=>"settingreceived",
        "where"=>"1 ORDER BY money ASC "
    ];

    $result2=$db->Select($param2);

    $i=0;
    if($result2)
    while($row2=mysqli_fetch_assoc($result2))
    {
        $data["list"][$i]=[
            "money"=>$row2["money"],
            "percentsend"=>$row2["percentsend"]
        ];
        $i++;
    }
    
    echo json_encode($data);
}
else 
{
    $data=[
        "extractmax"=>"",
        "list"=>[]
    ];
    echo json_encode($data);
}
?>