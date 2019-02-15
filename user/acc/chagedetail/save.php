<?php
include_once('../../../apps/bootstrap.php');
$rt = new apps_libs_Router();
$user = new apps_libs_UserLogin();
if (!$user->CheckUser()) $rt->LoginPage();
$uti = new apps_libs_Utilities();
$db=new apps_libs_Dbconn();
$rt = new apps_libs_Router();
if ($rt->GetPost('submit')) {
    $idacc = $uti->EditDataImportDB($user->GetAcc());
    $iddetalacc = $uti->EditDataImportDB(GetIDDetailAcc($idacc));
    $name = $uti->EditDataImportDB($rt->GetPost('name'));
    $identitycard = $uti->EditDataImportDB($rt->GetPost('identitycard'));
    $maill = $uti->EditDataImportDB($rt->GetPost('maill'));
    $phonenumber = $uti->EditDataImportDB($rt->GetPost('phonenumber'));
    if ($name == '' ||$name==null) 
    {
        echo 'Lỗi dữ liệu';
        die();
    }
    else {
        $db = new apps_libs_Dbconn();
        $img = '';
        if ($rt->GetPost('imgname')) {
            $firtfile = $rt->GetLink() . "/" . $rt->GetPost('imgname');
            $duoi = explode('.', $firtfile); // tách chuỗi khi gặp dấu .
            $duoi = $duoi[(count($duoi) - 1)];//lấy ra đuôi file
            $new = '/admin/img/avata/' . $idacc . '.' . $duoi;
            
            if (copy($firtfile, $rt->GetLink() . $new))
                $img = $new;
                //unlink($rt->GetLink().$rt->GetPost('imgname'));
                //$rt->DeleteFileOnPath($rt->GetLink()."admin/img/rec");
        }
        $param2 = [
            "from" => "detailacc",
            "param" => [
                "col" => [
                    "name",
                    "identitycard",
                    "maill",
                    "phonenumber",
                    "linkimg"
                ],
                "data" => [
                    "'" . $name . "'",
                    "'" . $identitycard . "'",
                    "'" . $maill . "'",
                    "'" . $phonenumber . "'",
                    "'" . $img . "'",
                ]
            ],
            "where" => "id='" . $iddetalacc . "'"
        ];
        if ($db->Update($param2)) {
            echo 'Lưu thành công!';
        } else echo 'Lỗi #221200';
    }
}

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

?>