<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();
$id = $rt->GetPost("id");

$db=new apps_libs_Dbconn();

$delete=[
    "from"=>"acc",
    "where"=>"id='".$id."'"
];
$db->Delete($delete);

$delete=[
    "from"=>"detailacc",
    "where"=>"idacc='".$id."'"
];
$db->Delete($delete);

$param=[
    "select"=>"id",
    "from"=>"historyproduct",
    "where"=>"idacc='".$id."'"
];
$result=$db->Select($param);
while($row=mysqli_fetch_assoc($result))
{
    $delete=[
        "from"=>"listproduct",
        "where"=>"idhp='".$row['id']."'"
    ];
    $db->Delete($delete);
}

$delete=[
    "from"=>"listproduct",
    "where"=>"idacc='".$id."'"
];
$db->Delete($delete);

$delete=[
    "from"=>"relationshipacc",
    "where"=>"dadacc='".$id."'"
];
$db->Delete($delete);

$delete=[
    "from"=>"relationshipacc",
    "where"=>"children='".$id."'"
];
$db->Delete($delete);
echo "Xóa Thành Công !";
?>