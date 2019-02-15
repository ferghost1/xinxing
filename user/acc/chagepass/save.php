<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckUser()) $rt->LoginPage();
$user=new apps_libs_UserLogin();
$uti=new apps_libs_Utilities();

$id=$uti->EditDataImportDB($user->GetAcc());

if ($rt->GetPost('submit'))
{
    $pass=$uti->EditDataImportDB($rt->GetPost("pass"));
    $newpass=$uti->EditDataImportDB($rt->GetPost("newpass"));
    $repeatnewpass=$uti->EditDataImportDB($rt->GetPost("repeatnewpass"));

    if(!$pass||!$newpass||!$repeatnewpass
    ||$pass==""||$newpass==""||$repeatnewpass=="") 
    {
        echo "Mời nhập đủ thông tin";
        die();
    }

    $pass=(new apps_libs_Utilities())->EditDataImportDB(rtrim($pass));
    $newpass=(new apps_libs_Utilities())->EditDataImportDB(rtrim($newpass));
    $repeatnewpass=(new apps_libs_Utilities())->EditDataImportDB(rtrim($repeatnewpass));

    if($newpass!=$repeatnewpass)
    {
        echo "Mật khẩu mới và nhập lại mật khẩu không trùng khớp";
        die();
    }

    $db=new apps_libs_Dbconn();

    $query = ([
        "select" => "id,type",
        "from" => "acc",
        "where" => "id='" . $id . "' and pass='" . md5($pass) . "'"
    ]);
    $result = $db->SelectOne($query);
    $row = mysqli_fetch_assoc($result);

    if($row)
    {
        $param=[
            "from"=>"acc",
            "where"=>"id='".$id."'",
            "param"=>[
                "col"=>[
                    "pass"
                ],
                "data"=>[
                    "\"".md5($newpass)."\""
                ]
            ]
        ];
        if($db->Update($param))
        {
            echo "Thay đổi mật khẩu thành công!";
        }else echo "Có lỗi xảy ra";
    }
    else echo "Mật khẩu của bạn không đúng";
}
?>