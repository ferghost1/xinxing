<?php
class apps_calculate_statistics
{
    public function apps_calculate_statistics()
    {

    }

    public function Statistics($time)
    {
        $calacc = new apps_calculate_calculateacc();
        $data = null;
        $count = $this->GetCountNumber();
        
        $number = 0;

        for ($i = 0; $i < $count; $i++) {
            $id = $this->GetUserLocation($i);
            $user = $calacc->GetUser($id);
            $phonenumber=$this->GetPhoneNumber($id);
            $name = $calacc->GetUserName($id);
           
            $revenue = $this->GetRevenue($id, $time); 
            $buya = $this->GetListBuyA($id, $time);
            $getmoneysun = $this->GetMoneySun($id, $time);
            $getmoneybuyaagency = $this->GetMoneyBuyAAgency($id, $time);

            $bank=$this->GetBank($id);
            
            if ($buya > 0 || $getmoneysun > 0 || $getmoneybuyaagency > 0) {
                $data[$number] = [
                    "user" => $user,
                    "name" => $name,
                    "phonenumber"=>$phonenumber,
                   
                    "revenue" => $revenue ,
                    "buya" => $buya,
                    "getmoneysun" => $getmoneysun,
                    "getmoneybuyaagency" => $getmoneybuyaagency,
                    "bank"=>$bank
            
                ];
                $number++;
            }
        }

        return $data;
    }

    public function StatisticsProduct($time)
    {
        $data = null;
        $param=[
            "select"=>"acc.user, acc.repurchase_money acc_repurchase, detailacc.name as username ,  lp.name, lp.price, lp.quantity, DATE(hp.timecreate) as timecreate, hp.repurchase_money, hp.repurchase_add,  hp.repurchase_remain",
            "from"=>"historyproduct hp,listproduct lp,acc,detailacc",
            "where"=>"hp.id=lp.idhp and hp.idacc=acc.id and acc.id=detailacc.idacc and MONTH(hp.timecreate)=".$time["month"]." and YEAR(hp.timecreate)=".$time["year"]." ORDER BY acc.user"
        ];
        $db=new apps_libs_Dbconn();
        $result=$db->Select($param);

        while($row=mysqli_fetch_assoc($result))
        {
            
            $data[]=[
                "user"=>$row["user"],
                "username"=>$row["username"],
                "nameproduct"=>$row["name"],
                "price"=>$row["price"],
                "quantity"=>$row["quantity"],
                "total"=>$row["price"]*$row["quantity"],
                "repurchase_money"=>$row["repurchase_money"],
                "repurchase_add"=>$row["repurchase_add"],
                "repurchase_remain"=>$row["repurchase_remain"],
                "timecreate"=>$row["timecreate"]
            ];
        }
        return $data;
    }

    public function GetCountNumber()
    {
        $param = [
            "select" => "COUNT(*) as count",
            "from" => "acc",
            "where" => "acc.type='user'"
        ];
        $db = new apps_libs_Dbconn();
        $result = $db->Select($param);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row["count"] ? $row["count"] : 0;
        } else return 0;

    }
    public function GetUserLocation($number)
    {
        $param = [
            "select" => "id",
            "from" => "acc",
            "where" => "acc.type='user' LIMIT " . $number . ",1"
        ];
        $db = new apps_libs_Dbconn();
        $result = $db->Select($param);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row["id"] ? $row["id"] : "";
        } else return "";
    }
    //====================
    // Lấy số điện thoại
    public function GetPhoneNumber($id)
    {
        $param=[
            "select"=>"phonenumber",
            "from"=>"detailacc",
            "where"=>"idacc='".$id."'"
        ];
        $db=new apps_libs_Dbconn();
        $result=$db->Select($param);
        if($result)
        {
            $row=mysqli_fetch_assoc($result);
            $data=$row["phonenumber"];
            return $data;
        }
        else 
        {
            return "";
        }
    }
    //=====================
    // Lấy thôn tin ngân hàng
    public function GetBank($id)
    {
        $param=[
            "select"=>"bank,bankaccountname,bankaccountnumber",
            "from"=>"detailacc",
            "where"=>"idacc='".$id."'"
        ];
        $db=new apps_libs_Dbconn();
        $result=$db->Select($param);
        if($result)
        {
            $row=mysqli_fetch_assoc($result);
            $data=[
                "bank"=>$row["bank"],
                "bankaccountname"=>$row["bankaccountname"],
                "bankaccountnumber"=>$row["bankaccountnumber"],
            ];
            return $data;
        }
        else 
        {
            $data=[
                "bank"=>"",
                "bankaccountname"=>"",
                "bankaccountnumber"=>"",
            ];
            return $data;
        }
    }

    // Doanh Thu Từ Tái Mua Hàng 
    function GetListBuyA($id, $time)
    {
        $firtbuy = $this->GetFistTimeBuy($id);
        if (!$firtbuy) return 0;

        $uti = new apps_libs_Utilities();
        $cal = new apps_calculate_calculate();

        $apl = $cal->GetAPL($time);

        $total = 0;
        $milestones = 0;
        $paidnow = 0;
        $paid = 0;
        $unpaid = 0;

        if (($firtbuy["month"] <= $time["month"] && $time["year"] == $firtbuy["year"]) || $firtbuy["year"] < $time["year"])
            do {
            $total += $this->GetRevenue($id, $firtbuy);

            $milestones = $this->GetMoneyReturn($total, $time);
            $number = ((int)($total / $apl["accumulate"]));
            $paidnow = $this->GetMoneyshare($firtbuy) * $number;
            $paid += $paidnow;

            $firtbuy = $uti->PlusDateMonth($firtbuy);
        } while (($firtbuy["month"] <= $time["month"] && $time["year"] == $firtbuy["year"]) || $firtbuy["year"] < $time["year"]);

        if ($paid > $milestones) $paid = $milestones;
        else $unpaid = $milestones - $paid;


        return $paidnow;
    }

    function GetRevenue($id, $time)
    {
        $calacc = new apps_calculate_calculateacc();
        $revenue = $calacc->Revenue($id, $time);
        return $revenue;
    }

    function GetRevenueAbout($id, $time)
    {
        $calacc = new apps_calculate_calculateacc();
        $revenueabout = $calacc->RevenueAbout($id, $time);
        return $revenueabout;
    }

    function GetMoneyshare($time)
    {
        $cal = new apps_calculate_calculate();
        $moneyshare = $cal->CalValueShare($time);
        return $moneyshare;
    }
    function GetShare($id, $time)
    {
        $calacc = new apps_calculate_calculateacc();
        return $calacc->Share($id, $time);
    }

    function GetAccumulate($time)
    {
        $cal = new apps_calculate_calculate();
        $apl = $cal->GetApl($time);
        return $apl["accumulate"];
    }

    function GetMoneyReturn($total, $time)
    {
        $cal = new apps_calculate_calculate();
        $fln = $cal->GetFLN();
        $apl = $cal->GetAPL($time);
        $number_s = 0;
        $number_s = ((int)($total / $apl["accumulate"]));
        $data = 0;
        for ($i = 0; $i < $number_s; $i++) {
            $percent;
            if ($i == 0)
                $percent = 0;
            else if ($i == 1)
                $percent = $fln["firstreturnshare"];
            else $percent = (($fln["nextreturnshare"] * ($i - 1) + $fln["firstreturnshare"]) <= $fln["limitreturnshare"]) ? $fln["firstreturnshare"] + $fln["nextreturnshare"] * ($i - 1) : $fln["limitreturnshare"];
            $data += $apl["accumulate"] + ($apl["accumulate"] * $percent) / 100;
        }
        return $data;
    }

    function GetFistTimeBuy($id)
    {
        $param = [
            "select" => "timecreate",
            "from" => "historyproduct",
            "where" => "idacc='" . $id . "' ORDER BY timecreate ASC"
        ];
        $db = new apps_libs_Dbconn();
        $result = $db->SelectOne($param);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $uti = new apps_libs_Utilities();
            return $row["timecreate"] ? $uti->GetDateMonthInDataBase($row["timecreate"], true) : null;
        } else return null;
    }

    //======================================================

    //Doanh Thu Từ Đại Lý Con
    function GetMoneySun($id, $time)
    {
        $calacc = new apps_calculate_calculateacc();
        $data = $calacc->MoneyAgencyShare($id, $time);
        $result = 0;
        if ($data["f1"])
            foreach ($data["f1"] as $item) {
            $result += $item["moneypercent"];
        }

        if ($data["f2f5"])
            foreach ($data["f2f5"] as $item) {
            foreach ($item as $it) {
                $result += $it["moneypercent"];
            }
        }

        return $result;
    }
    // Doanh Thu Tái Mua Hàng Đại Lý
    function GetMoneyBuyAAgency($id, $time)
    {
        $calacc = new apps_calculate_calculateacc();
        $data = $calacc->ShareAgency($id, $time);
        return $data["dad"]["moneya"];
    }
}