<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckUser()) $rt->LoginPage();
$uti=new apps_libs_Utilities();
$id = $uti->EditDataImportDB($rt->GetPost('id'));
$time = $uti->EditDataImportDB($rt->GetPost('time'));

if (!$id || !$time) {
    die();
}
$time = (new apps_libs_Utilities())->GetDateMonth($time);
$calacc = new apps_calculate_calculateacc();
$data = $calacc->MoneyAgencyShare($id, $time,TRUE);
if ($data) {
    echo CreateTable($data);
}
else echo "<span style=\"color:red\">Không có dữ liệu</span>";
?>

<?php
function CreateTable($data)
{
    if (!$data["f2f5"]&&!$data["f1"]) return "Không có dữ liệu";
    $table = "<table id='table' class='table table-striped table-hover'>";
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
        if($data["f1"])
        foreach ($data["f1"] as $item) {
            if($item["money"]==0) continue;
            $table .= "<tr>
                        <td>" . $i . "</td>
                        <td>" . $item["user"] . "</td>
                        <td>" . $uti->EditNumber($item["money"]) . "</td>
                        <td>" . $item["percent"] . " %</td>
                        <td>" . $uti->EditNumber($item["moneypercent"]) . "</td>
                    </tr>";
            $i++;
        }

        if($data["f2f5"])
        foreach ($data["f2f5"] as $item) {
            foreach ($item as $it) {
                if($it["money"]==0) continue;
                $table .= "<tr>
                        <td>" . $i . "</td>
                        <td>" . $it["user"] . "</td>
                        <td>" . $uti->EditNumber($it["money"]) . "</td>
                        <td>" . $it["percent"] . " %</td>
                        <td>" . $uti->EditNumber($it["moneypercent"]) . "</td>
                    </tr>";
                $i++;
            }
        }
    }
    $table .= "</table> ";

    return $table;
}

?>