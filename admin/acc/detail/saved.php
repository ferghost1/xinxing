<?php
include_once('../../../apps/bootstrap.php');
$rt = new apps_libs_Router();
$user = new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();
function GetIDDetailAcc($idacc)
{
    $db = new apps_libs_Dbconn();
    $param = [
        "select" => "id",
        "from" => "detailacc",
        "where" => "idacc='" . $idacc . "'"
    ];
    $result = $db->SelectOne($param);
    $row = mysqli_fetch_assoc($result);

    return $row['id'];
}

$rt = new apps_libs_Router();
if ($rt->GetPost('submit')) {
    $idacc = $rt->GetPost('id');
    $iddetalacc = GetIDDetailAcc($idacc);
    $pass = $rt->GetPost('pass');
    $name = $rt->GetPost('name');
    $identitycard = $rt->GetPost('identitycard');
    $maill = $rt->GetPost('maill');
    $phonenumber = $rt->GetPost('phonenumber');
    $bank = $rt->GetPost('bank');
    $bankaccountname = $rt->GetPost('bankaccountname');
    $bankaccountnumber = $rt->GetPost('bankaccountnumber');

    $active = $rt->GetPost('active');
    $active = trim($active);
    if ($active != "yes" && $active != "no") $active = 'no';
    if (!$maill) $maill = "";
    if (!$identitycard) $identitycard = "";
    if (!$phonenumber) $phonenumber = "";
    if (!$bank) $bank = "";
    if (!$bankaccountname) $bankaccountname = "";
    if (!$bankaccountnumber) $bankaccountnumber = "";


    if ($name == '' || $name == null) echo 'Lỗi dữ liệu';
    $db = new apps_libs_Dbconn();
    $img = '';
    if ($rt->GetPost('imgname')) {
        if (!strpos($rt->GetPost('imgname'), "rec")) {
            $img = $rt->GetPost('imgname');
        } else {
            $firtfile = $rt->GetLink() . $rt->GetPost('imgname');
            $duoi = explode('.', $firtfile); // tách chuỗi khi gặp dấu .
            $duoi = $duoi[(count($duoi) - 1)];//lấy ra đuôi file
            $new = 'admin/img/avata/' . $idacc . '.' . $duoi;
            if (copy($rt->GetLink() . $rt->GetPost('imgname'), $rt->GetLink() . $new))
                $img = $new;
                //unlink($rt->GetLink().$rt->GetPost('imgname'));
            $rt->DeleteFileOnPath($rt->GetLink() . "admin/img/rec");
        }
    }
    $param1;
    if ($pass == '') {
        $param1 = [
            "from" => "acc",
            "param" => [
                "col" => [
                    "active"
                ],

                "data" => [
                    "'" . $active . "'"
                ]
            ],
            "where" => "id='" . $idacc . "'"
        ];
    } else $param1 = [
        "from" => "acc",
        "param" => [
            "col" => [
                "pass", "active"
            ],

            "data" => [
                "'" . md5($pass) . "'",
                "'" . $active . "'"
            ]
        ],
        "where" => "id='" . $idacc . "'"
    ];
    if ($db->Update($param1)) {
        $param2 = [
            "from" => "detailacc",
            "param" => [
                "col" => [
                    "name",
                    "identitycard",
                    "maill",
                    "phonenumber",
                    "linkimg",
                    "bank",
                    "bankaccountname",
                    "bankaccountnumber",
                ],
                "data" => [
                    "'" . $name . "'",
                    "'" . $identitycard . "'",
                    "'" . $maill . "'",
                    "'" . $phonenumber . "'",
                    "'" . $img . "'",
                    "'" . $bank . "'",
                    "'" . $bankaccountname . "'",
                    "'" . $bankaccountnumber . "'"
                ]
            ],
            "where" => "id='" . $iddetalacc . "'"
        ];
        if ($db->Update($param2)) {
            echo 'Lưu thành công!';
        } else echo 'Lỗi #221200';

    } else echo 'Lỗi #121200';
}