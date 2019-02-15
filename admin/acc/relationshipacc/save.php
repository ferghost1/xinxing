<?php
include_once('../../../apps/bootstrap.php');
$rt = new apps_libs_Router();
$user = new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();
$db = new apps_libs_Dbconn();
if ($rt->GetPost('submit')) {
    $id = $rt->GetPost('id');
    $json = $rt->GetPost('data');
    //die($json);
    $data = json_decode($json, true);
    if ($id == null || $id == "" || $data == null || $data == "") die("Lỗi dữ liệu");
    if (count($data["dad"]) > 1) die("Lỗi! Chỉ được có một cha");
    $paramdel1 = [
        "from" => "relationshipacc",
        "where" => "children='" . $id . "'"
    ];
    $db->Delete($paramdel1);
    $paramdel2 = [
        "from" => "relationshipacc",
        "where" => "dadacc='" . $id . "'"
    ];
    $db->Delete($paramdel2);
    if (count($data["dad"]) > 0) {


        $ids = $db->CreateID("relationshipacc", "id");
        $param1 =
            [
            "from" => "relationshipacc",
            "param" => [
                "col" => "id,dadacc,children",
                "data" => [
                    "'" . $ids . "'",
                    "'" . $data["dad"][0] . "'",
                    "'" . $id . "'",
                ]
            ]
        ];
        $db->Insert($param1);
    }

    if (count($data["child"]) > 0)
        foreach ($data["child"] as $item) {
        $ids = $db->CreateID("relationshipacc", "id");
        $param2 = [
            "from" => "relationshipacc",
            "param" => [
                "col" => "id,dadacc,children",
                "data" => [
                    "'" . $ids . "'",
                    "'" . $id . "'",
                    "'" . $item . "'",
                ]
            ]
        ];
        $db->Insert($param2);
    }
    echo "Lưu thành công!";
}
?>