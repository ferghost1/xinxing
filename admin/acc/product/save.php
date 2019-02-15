<?php
include_once '../../../apps/bootstrap.php';
$rt   = new apps_libs_Router();
$user = new apps_libs_UserLogin();
$db   = new apps_libs_Dbconn();

if (!$user->CheckAdmin()) {
    $rt->LoginPage();
}

if ($rt->GetPost('submit')) {
    $id            = $rt->GetPost('id');
    $json          = $rt->GetPost('data');
    $name          = "a";
    $time          = $rt->GetPost('time');
    $note          = $rt->GetPost('note');
    $idhtrss       = $rt->GetPost('idhtr');
    $is_repurchase = $rt->GetPost('is_repurchase');
    $data          = json_decode($json, true);

    if ($id == '' || $id == null || $json == '' || $json == "[]" || $json == null || $name == '' || $name == null || $time == '' || $time == null)
            echo CreateJson("Mời nhập đủ dữ liệu");
    else {
        try {

            // Nếu có id đơn hàng(đơn đã tồn tại) thì xóa đơn cử tạo đơn mới (Chế độ update)
            if ($idhtrss != "") {
                // Lấy đơn hàng cũ để xử lý tiền
                // $his_repurchase = $db->trans_query("select * from historyproduct where id = $idhtrss",true);
                // Trước khi xóa đơn lấy tiền tái mua của đơn trước cộng lại vào tái mua của acc, trừ ra phần tái mua từ hoa hồng đơn
                $db->transaction('autocommit');
                $db->trans_voidQuery("update historyproduct as his, acc set acc.repurchase_money = acc.repurchase_money + his.repurchase_money - his.repurchase_add where acc.id = his.idacc and his.id = '{$idhtrss}' ");
                $db->trans_voidQuery("delete from historyproduct where id = '{$idhtrss}'");
                $db->trans_voidQuery("delete from listproduct where idhp = '{$idhtrss}'");
            }

            $data = json_decode($json, true);
            // Kiểm tra co dữ liệu mới thực thi
            if (CheackData($data)) {
                $idhtr;
                if ($idhtrss == "") {
                    // param segment 3 to point out transactioning
                    $idhtr = $db->CreateID('historyproduct', 'id', true);
                } else {
                    $idhtr = $idhtrss;
                }
                // Thêm đơn hàng vào bảng historyproduct
                $param = [
                    "from"  => "historyproduct",
                    "param" => [
                        "col"  => "id,idacc,name,note,timecreate",
                        "data" => [
                            "'" . $idhtr . "'",
                            "'" . $id . "'",
                            "'" . $name . "'",
                            "'" . $note . "'",
                            "'" . $time . "'",
                        ],
                    ],
                ];
                // Thêm Chi tiết đơn hàng
                $db->Insert($param, '', true);
                foreach ($data as $item) {
                    $idlpd  = $db->CreateID('listproduct', 'id', true);
                    $param1 = [
                        "from"  => "listproduct",
                        "param" => [
                            "col"  => "id,idhp,name,price,quantity",
                            "data" => [
                                "'" . $idlpd . "'",
                                "'" . $idhtr . "'",
                                "'" . $item['name'] . "'",
                                "'" . $item['price'] . "'",
                                "'" . $item['quantity'] . "'",
                            ],
                        ],
                    ];
                    // Nếu insert chi tiết đơn lỗi thì throw except r rollback từ đầu
                    $db->Insert($param1, '', true);
                }
                // Tính tiền tái mua hàng
                // $acc_repurchase tiền hiện tại của acc
                $acc_repurchase = $db->trans_query("select * from acc where id = '{$id}'", true)->repurchase_money;
                // $repurchase_rate tỉ tièn tái tiêu dùng
                $repurchase_rate = $db->trans_query("select * from revenue_share where id = 1", true)->repurchase_rate;
                $total = 0;
                foreach ($data as $val) {
                    $total += $val['quantity'] * $val['price'];
                }
                $repurchase_get = $total * ($repurchase_rate/100);
                // Kiểm tra có dùng tái mua ko
                if ($is_repurchase == 'true') {
                    // Trường hợp tièn hóa đơn lớn hơn tái mua và tái múa ko âm
                    if ($total >= $acc_repurchase && $acc_repurchase >= 0) {
                        //trừ hết tái mau acc, ghi tái mua dùng và tái mua tái mua được cộng
                        $db->trans_voidQuery("update acc 
                            set repurchase_money = $repurchase_get 
                            where id = '{$id}'");
                        $db->trans_voidQuery("update historyproduct 
                            set repurchase_money = {$acc_repurchase}, repurchase_add = {$repurchase_get} 
                            where id = '{$idhtr}'");
                    }
                    //Tái mua âm tái mua dùng bằng 0 và cộng % tái mua vào acc
                    elseif($total >= $acc_repurchase && $acc_repurchase < 0){
                        $db->trans_voidQuery("update acc set repurchase_money = repurchase_money + '{$repurchase_get}' where id = '{$id}'");
                        $db->trans_voidQuery("update historyproduct 
                            set repurchase_add = {$repurchase_get}
                            where id = '{$idhtr}'");
                    }
                    // Tiền tái mua nhiều hơn đơn
                    else{
                        $repurchase_remain = $acc_repurchase - $total + $repurchase_get; 
                        $db->trans_voidQuery("update acc set repurchase_money = {$repurchase_remain} where id = '{$id}'");
                        $db->trans_voidQuery("update historyproduct set repurchase_money = {$total}, repurchase_add = {$repurchase_get} where id = '{$idhtr}'");
                    }
                }else{
                    //Nếu ko có dùng tái mua thì add tái mua cho acc, add tái mua cho history
                    $db->trans_voidQuery("update acc set repurchase_money = repurchase_money + '{$repurchase_get}' where id = '{$id}'");

                    $db->trans_voidQuery("update historyproduct
                        set repurchase_add = {$repurchase_get} 
                        where id = '{$idhtr}'");
                    
                }
                $db->trans_voidQuery("update historyproduct, acc 
                        set historyproduct.repurchase_remain = acc.repurchase_money
                        where historyproduct.id = '{$idhtr}'
                              and historyproduct.idacc = acc.id
                        ");

            }
            $db->transaction('commit');
            echo CreateJson("Lưu thành công!", $idhtr);
        } catch (Exception $e) {
            $db->transaction('rollback');
            echo CreateJson("Lỗi: " . $e->getMessage());
            die;
        }
    }
}
// Kiểm tra hiện tại có dùng tái mua ko
// Có thì lấy tông tiền tái mua của acc tính lại giá ko thì thôi
else {
    echo 'Error';
}

function CreateJson($mess, $id = null)
{

    if ($id == null) {
        $result = [
            "mess" => $mess,
            "id"   => "",
        ];
        return json_encode($result);
    } else {
        $result = [
            "mess" => $mess,
            "id"   => $id,
        ];
        return json_encode($result);
    }
}

function CheackData($data)
{
    foreach ($data as $item) {
        foreach ($item as $it) {
            if ($it == "") {
                return false;
            }

        }
    }
    return true;
}
