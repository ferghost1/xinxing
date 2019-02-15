<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();

$id = $rt->GetPost('id');
$time = $rt->GetPost('time');

if (!$id || !$time) {
    die();
}
$time = (new apps_libs_Utilities())->GetDateMonth($time);
$calacc = new apps_calculate_calculateacc();
$data = $calacc->ShareAgency($id, $time);
if ($data) {
    $uti=new apps_libs_Utilities();
    echo "
    <div class=\"panel panel-success\">
    <div class=\"panel-heading\">
        <h3 class=\"panel-title\">DOANH SỐ TÁI TIÊU DÙNG</h3>
    </div>
    <div class=\"panel-body\">
        <div class=\"alert alert-info\">
        Số Tiền Phát Sinh: ".$uti->EditNumber($data["dad"]["total"])."<br/>
        Số Tiền Được Chia: ".$uti->EditNumber($data["dad"]["moneyshare"])."<br/>
        Số Tiền Chia Xuống: ".$uti->EditNumber($data["dad"]["moneysharedown"])."<br/>
        Số Tiền Còn Lại: ".$uti->EditNumber($data["dad"]["moneya"])."<br/>
        </div>
        ".CreateTableChild($data)."
    </div>
</div>
    ";
}
else echo "<span style=\"color:red\">Không có dữ liệu</span>";
?>

<?php
function CreateTableChild($data)
{
    if (!isset($data["child"])) return "Không có dữ liệu";
    $table = "<table class='table table-striped table-hover'>";
    $table .= "<tr>
        <th>STT</th>
        <th>Mã Số Khách Hàng</th>
        <th>Doanh Số Phát Sinh</th>
        <th>Tỷ Lệ Chiết Khấu</th>
        <th>Số Tiền Được Nhận</th>
    </tr>";

    $number = 1;
    
    if ($data) {
        $i=1;
        $uti=new apps_libs_Utilities();
        if($data["child"])
        foreach ($data["child"] as $item) {
            if($item["money"]==0)continue;
            $table .= "<tr>
                        <td>" . $i . "</td>
                        <td>" . $item["user"] . "</td>
                        <td>" . $uti->EditNumber($item["money"]) . "</td>
                        <td>" . $item["percent"] . " %</td>
                        <td>" . $uti->EditNumber($item["moneypercent"]) . "</td>
                    </tr>";
            $i++;
        }
    }
    $table .= "</table> ";

    return $table;
}

?>