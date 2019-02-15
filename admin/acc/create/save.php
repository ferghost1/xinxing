<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();     
if ($rt->GetPost('submit')) {
    $user = $rt->GetPost('user');
    $pass = $rt->GetPost('pass');
    $name = $rt->GetPost('name');
    $identitycard = $rt->GetPost('identitycard');
    $maill = $rt->GetPost('maill');
    $timecreate=$rt->GetPost("timecreate");
    $timecreate=str_replace("-","",$timecreate);
    $phonenumber = $rt->GetPost('phonenumber');
    $active=$rt->GetPost('active');
    $bank=$rt->GetPost('bank');
    $bankaccountname=$rt->GetPost('bankaccountname');
    $bankaccountnumber=$rt->GetPost('bankaccountnumber');

    $active=trim($active);
    if($active!="yes"&&$active!="no") $active='no';
    
    if(!$maill)$maill="";
    if(!$identitycard)$identitycard="";
    if(!$phonenumber)$phonenumber="";
    if(!$bank)$bank="";
    if(!$bankaccountname)$bankaccountname="";
    if(!$bankaccountnumber)$bankaccountnumber="";
    if ($user == '' || $user == null || $pass == '' || $pass == null
        || $name == '' || $name == null
        ||$timecreate==''||$timecreate==null) echo 'Lỗi dữ liệu! Bạn nhập thiếu thông tin';
    else {
        $db = new apps_libs_Dbconn();
        if ($db->CheackValue('acc', 'user', '"' . $user . '"')) {
            echo 'Đã tồn tại tài khoản có tên đăng nhập là: "'.$user."\"";
            die();
        }
        $code=$rt->GetPost("code");
        if($code)
        if(!$db->CheackValue("acc","user","'".$code."'"))
        {
            echo "Không tồn tại người giới thiệu ".$code;
            die();
        }
        else $code=$db->GetValue("acc","id","user='".$code."'");
        $idacc = $db->CreateID('acc', 'id');
        $iddetalacc = $db->CreateID('detailacc', 'id');
        $img = '';
        if ($rt->GetPost('imgname')) {
            $firtfile=$rt->GetLink().$rt->GetPost('imgname');
            $duoi = explode('.', $firtfile); // tách chuỗi khi gặp dấu .
            $duoi = $duoi[(count($duoi) - 1)];//lấy ra đuôi file
            $new = 'admin/img/avata/' . $idacc.'.'.$duoi;
            if(copy($rt->GetLink().$rt->GetPost('imgname'), $rt->GetLink().$new))
                $img = $new;
            $rt->DeleteFileOnPath($rt->GetLink()."admin/img/rec");
        }
        $type = 'user';
        if((new apps_libs_UserLogin())->CheckRoot())
        {
            $type=$rt->GetPost("type");
        }
        $param1 = [
            "from" => "acc",
            "param" => [
                "col" => "id,user,pass,type,timecreate,active",
                "data" => [
                    "'" . $idacc . "'",
                    "'" . $user . "'",
                    "'" . md5($pass) . "'",
                    "'" . $type . "'",
                    "'".$timecreate."'",
                    "'".$active."'"
                ]
            ]
        ];
        if (!$db->Insert($param1)) {
            echo 'Lỗi #121200';
            die();
        }

        $param2 = [
            "from" => "detailacc",
            "param" => [
                "col" => "id,idacc,name,identitycard,maill,phonenumber,linkimg,bank,bankaccountname,bankaccountnumber",
                "data" => [
                    "'" . $iddetalacc . "'",
                    "'" . $idacc . "'",
                    "'" . $name . "'",
                    "'" . $identitycard . "'",
                    "'" . $maill . "'",
                    "'" . $phonenumber . "'",
                    "'" . $img . "'",
                    "'" . $bank . "'",
                    "'" . $bankaccountname . "'",
                    "'" . $bankaccountnumber . "'",
                ]
            ]
        ];
        if (!$db->Insert($param2)) {
            echo 'Lỗi #221200';
            $param3 = [
                "from" => "detailacc",
                "where" => "id='" . $idacc . "'"
            ];
            $db->Delete($param3);
            die();
        }
        if($code&&$type=="user")
        {
            $idrelationshipacc=$db->CreateID("relationshipacc","id");
            $param=[
                "from" => "relationshipacc",
                "param" => [
                    "col" => "id,dadacc,children",
                    "data" => [
                        "'" . $idrelationshipacc . "'",
                        "'" . $code . "'",
                        "'" . $idacc . "'"
                    ]
                ]
                    ];
            $db->Insert($param);
        }
        echo 'Lưu thành công!    <a class="btn btn-lg btn-link" href="?r=acc&p=menu&id='.$idacc.'">Chuyển qua trang quản lý <i class="fa fa-arrow-circle-right"></i></a>';
    }
}