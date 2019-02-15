<?php
define("LIMIT_CUP", 11500000);
class apps_calculate_calculateacc
{
    public function apps_calculate_calculateacc()
    {

    }

    public function GetChild($id, $linkid = null)
    {
        $param = [
            "select" => "relationshipacc.children",
            "from" => "relationshipacc",
            "where" => "dadacc='" . $id . "'"
        ];
        $db = new apps_libs_Dbconn();
        $result = $db->Select($param);
        $data = null;

        $i = 0;
        if (!$linkid) $linkid = $id;
        else $linkid .= (" " . $id);
        if ($result)
            while ($row = mysqli_fetch_assoc($result)) {
            if (!$row) continue;
            $data[$i] = [
                "linkid" => $linkid,
                "id" => $row["children"],
                "user" => $this->GetUser($row["children"]),
                "child" => null
            ];
            $i++;
        }
        return $data;
    }

    public function GetAllChild($id, $linkid = null, $level = null)
    {
        if ($this->CheckLinkId($linkid, $level)) {
            $listchild = $this->GetChild($id, $linkid);
            if (!$listchild) return null;
            for ($i = 0; $i < sizeof($listchild); $i++)
                $listchild[$i]["child"] = $this->GetAllChild($listchild[$i]["id"], $listchild[$i]["linkid"], $level);
            return $listchild;
        } else {
            return null;
        }
    }
    public function CheckLinkId($linkid, $level = null)
    {
        if (!$level) $level = 20; //level acc con
        if (!$linkid) return true;
        if (sizeof(explode(" ", $linkid)) < $level) return true;
        else return false;
    }
    // Tổng tiền mua hàng
    public function Revenue($id, $time, $check = false)
    {
        if ($check) {
            $cal = new apps_calculate_calculate();
            $uti = new apps_libs_Utilities();
            $time_now = $uti->GetMonthNow();
            if (!$cal->CheckShowShare() && $time_now["month"] == $time["month"] && $time_now["year"] == $time["year"])
                return 0;
        }

        $param = [
            "select" => "SUM(listproduct.price * listproduct.quantity) as total",
            "from" => "listproduct,historyproduct,acc",
            "where" => "historyproduct.idacc='" . $id . "' and acc.id=historyproduct.idacc and historyproduct.id = listproduct.idhp and MONTH(historyproduct.timecreate)=" . $time['month'] . " and YEAR(historyproduct.timecreate)=" . $time['year'] . " GROUP BY acc.user"
        ];
        $db = new apps_libs_Dbconn();
        $result = $db->Select($param);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row ? $row["total"] : 0;
        } else return 0;
    }
    //Tổng tiền mua hàng các tháng trước tính cả tháng hiện tại
    public function RevenueAbout($id, $time, $check = false)
    {
        if ($check) {
            $cal = new apps_calculate_calculate();
            $uti = new apps_libs_Utilities();
            $time_now = $uti->GetMonthNow();
            if (!$cal->CheckShowShare() && $time_now["month"] == $time["month"] && $time_now["year"] == $time["year"])
                return 0;
        }

        $param = [
            "select" => "SUM(listproduct.price * listproduct.quantity) as total",
            "from" => "listproduct,historyproduct,acc",
            "where" => "historyproduct.idacc='" . $id . "' and acc.id=historyproduct.idacc and historyproduct.id = listproduct.idhp " . " and (MONTH(historyproduct.timecreate)<=" . $time['month'] . " and YEAR(historyproduct.timecreate)=" . $time['year'] . " or YEAR(historyproduct.timecreate)<" . $time['year'] . ")" . "GROUP BY acc.user"
        ];
        $db = new apps_libs_Dbconn();
        $result = $db->Select($param);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row ? $row["total"] : 0;
        } else return 0;
    }
    public function Share($id, $time)
    {
        $cal = new apps_calculate_calculate();
        $apl = $cal->GetApl($time);

        $revenue = $this->Revenue($id, $time);
        return $revenue ? (int)($revenue / $apl["accumulate"]) : 0;
    }

    public function ShareAgency($id, $time, $check = false)
    {   
        //Lấy tất cả child theo cây cấp độ cha - con, lấy id và username, nếu 1 phần từ có child thì là array k có là null
        // Dùng số lượng cha là linkid để tính cấp
        // Mỗi phần từ đều gửi lệnh sql nếu quá nhiều thì đơ
        $child = $this->GetAllChild($id, null, 1000);
        $cal = new apps_calculate_calculate();
        $data = null;
        $i = 0;
        $money_dad = 0;
        if ($child)
            foreach ($child as $item) {
            $money = $this->CalMoneyShareChild($item["child"], $time);
            
            //====================
            $uti = new apps_libs_Utilities();
            $times = $uti->MinusDateMonth($time);

            /*
            $cal = new apps_calculate_calculate();
            $list = $cal->GetEMPP()["list"];
            $max = count($list) > 0 ? $list[0]["money"] : 0;
             */

            $money_ck = $this->RevenueAbout($item["id"], $times, $check);
            $money_now = ((int)$this->Revenue($item["id"], $time));
            $money_minus = 0;
            if ($money_ck >= LIMIT_CUP)
            {
                $money_minus=$money_now;
                $money += $money_now;
            }             
            else
            {
                $money_minus=($money_now > (LIMIT_CUP - $money_ck)) ? $money_now - (LIMIT_CUP - $money_ck) : 0;
                $money += $money_minus;
            }
                

            //====================
            /*
            if ($money_ck > $max) {
                $money += (int)$this->Revenue($item["id"], $time,$check);
            }
             */
            $d = $this->CreateShareAgency($item["user"], $money-$money_minus, $cal->GetPercent($money));
            $data["child"][$i] = $d;
            $i++;
            $money_dad += $money;
        }

        $moneysharedown = 0;
        if ($data)
            foreach ($data["child"] as $item) {
            $moneysharedown += $item["moneypercent"];
        }

        $dad = [
            "total" => $money_dad,
            "moneyshare" => ($cal->GetPercent($money_dad) * $money_dad) / 100,
            "moneysharedown" => $moneysharedown,
            "moneya" => (($cal->GetPercent($money_dad) * $money_dad) / 100) - $moneysharedown
        ];
        $data["dad"] = $dad;
        return $data;
    }

    function CalMoneyShareChild($child, $time, &$data = null)
    {
        if ($child) {
            foreach ($child as $item) {

                $uti = new apps_libs_Utilities();
                $times = $uti->MinusDateMonth($time);
                /*
                $cal = new apps_calculate_calculate();
                $list = $cal->GetEMPP()["list"];
                $max = count($list) > 0 ? $list[0]["money"] : 0;
                
                $money_ck = $this->RevenueAbout($item["id"], $times);
                if ($money_ck > $max)
                    $data += ((int)$this->Revenue($item["id"], $time));
                 */
                $money_ck = $this->RevenueAbout($item["id"], $times);
                $money_now = ((int)$this->Revenue($item["id"], $time));
                if ($money_ck >= LIMIT_CUP)
                    $data += $money_now;
                else
                    $data += ($money_now > (LIMIT_CUP - $money_ck)) ? $money_now - (LIMIT_CUP - $money_ck) : 0;
            }
            foreach ($child as $item) {
                if ($item["child"])
                    $this->CalMoneyShareChild($item["child"], $time, $data);
            }
        }
        return $data ? $data : 0;
    }
    function sortChildren($children, $revenue_rate, $time){
        if(!is_array($children))
            return;
        foreach($children as $k => $v){
            $level = sizeof(explode(' ', $v['linkid']));
            //Nếu cấp của user lớn hơn cấp hh nhỏ nhất thì bằng cấp hh nhỏ nhất
            end($revenue_rate);
            $revenue = $level >= key($revenue_rate)? $revenue_rate[key($revenue_rate)]: $revenue_rate[$level];
            $temp = $v;
            // Lấy tt user cùng vs tính toán tiền cho user truyền vào
            $temp['data'] = $this->CreateMoneyAgencyShareChild($v['id'],$revenue, $time);
            unset($temp['child']);
            $arr[$level][] = $temp; 
            //Đệ quy để lấy và sắp xếp tất cả con của nó
            while (is_array($v['child'])) {
                foreach($v['child'] as $k => $v){
                    $level = sizeof(explode(' ', $v['linkid']));
                    end($revenue_rate);
                    $revenue = $level >= key($revenue_rate)? $revenue_rate[key($revenue_rate)]: $revenue_rate[$level];
                    $temp = $v;
                    // Lấy tt user cùng vs tính toán tiền cho user truyền vào
                    $temp['data'] = $this->CreateMoneyAgencyShareChild($v['id'],$revenue, $time);
                    unset($temp['child']);
                    $arr[$level][] = $temp; 
                }
            }
        }
        return $arr;
    }
    // Return mãng gồm f1 và f2f5 chứa username, tiền và %hh của user theo $id
    public function MoneyAgencyShare($id, $time, $check = false)
    {
        if ($check) {
            $cal = new apps_calculate_calculate();
            $uti = new apps_libs_Utilities();
            $time_now = $uti->GetMonthNow();
            // Nếu tháng truyền vào là tháng hiện tại thì ngưng vì chưa đủ dữ liệu
            if (!$cal->CheckShowShare() && $time_now["month"] == $time["month"] && $time_now["year"] == $time["year"])
                return null;
        }
        // Lấy tất cả cả child ở tất cả cấp
        $child = $this->GetAllChild($id);

        $cal = new apps_calculate_calculate();
        //Lấy phần trăm của các cấp
        $ll = $cal->GetLL();

        if ($child != null) {
            $i = 0;
            $dataf1 = null;
            $dataf2f5 = null;
            foreach ($child as $item) {
                //trả về mãng gồm user, %hh, tiền mua hàng tg tháng của f1
                $data = $this->CreateMoneyAgencyShareChild($item["id"], $ll["levelf1return"], $time);
                if ($data) {
                    $dataf1[$i] = $data;
                    $i++;
                }
            }
            $i = 0;
            //trả về mãng gồm user, %hh, tiền mua hàng tg tháng của f2 tơi f5 truyền vào đtuong child
            foreach ($child as $item) {
                $data = $this->MoneyAgencyShareChild($item["child"], $ll["levelf2f5return"], $time);
                if ($data) {
                    $dataf2f5[$i] = $data;
                    $i++;
                }
            }
            // echo '<pre>';
            // var_dump($dataf2f5);die;
            return [
                "f1" => $dataf1,
                "f2f5" => $dataf2f5
            ];
        } else return null;
    }
    public function custom_MoneyAgencyShare($id, $time, $check = false)
    {
        if ($check) {
            $cal = new apps_calculate_calculate();
            $uti = new apps_libs_Utilities();
            $time_now = $uti->GetMonthNow();
            // Nếu tháng truyền vào là tháng hiện tại thì ngưng vì chưa đủ dữ liệu
            if (!$cal->CheckShowShare() && $time_now["month"] == $time["month"] && $time_now["year"] == $time["year"])
                return null;
        }
        $db = new apps_libs_Dbconn();
        // Lấy % hoa hồng các cấp
        $revenue_rate = $db->query('select revenue from revenue_share',true);
        $revenue_rate = json_decode($revenue_rate->revenue,true);
        // Lấy tất cả cả child ở tất cả cấp
        $child = $this->GetAllChild($id);
        // echo '<pre>';
        // var_dump($child);
        // Lọc tất cả children theo mãng có key là cấp của usẻr
        $all_child = $this->sortChildren($child, $revenue_rate, $time);
        return $all_child;
        
    }
  
    function MoneyAgencyShareChild($child, $percent, $time, &$data = null)
    {
        $i = sizeof($data);
        if ($child) {
            foreach ($child as $item) {
                $dt = $this->CreateMoneyAgencyShareChild($item["id"], $percent, $time);
                if ($dt) {
                    $data[$i] = $dt;
                    $i++;
                }

            }
            //Nếu $child có cấp dưới thì tiếp tục 
            foreach ($child as $item) {
                if ($item["child"])
                    $this->MoneyAgencyShareChild($item["child"], $percent, $time, $data);
            }
        }
        return $data;
    }

    function CheckChild($listchild, $id, &$result = false)
    {
        foreach ($listchild as $item) {
            if ($item["id"] == $id) $result = true;
        }
        foreach ($listchild as $item) {
            if ($item["child"])
                $this->CheckChild($item["child"], $id, $result);
        }
        return $result;
    }
    // Trả về mãng tên user tổng tièn và hoa hồng của user dựa theo % truyền vào biến $percent
    function CreateMoneyAgencyShareChild($id, $percent, $time)
    {
        $uti = new apps_libs_Utilities();
        $times = $uti->MinusDateMonth($time);

        /*
        $cal = new apps_calculate_calculate();
        $max = 0;

        $empp = $cal->GetEMPP();
        if (sizeof($empp["list"]) > 0) $max = $empp["list"][0]["money"];
         */
        // Tổng tiền đơn hàng trước giò
        $revenueabout = $this->RevenueAbout($id, $times);
        if ($revenueabout > LIMIT_CUP) return null;
        //if ($revenueabout > $max) return null;
        // Tổng tiền đơn hàng tháng này
        $revenue = $this->Revenue($id, $time);

        $revenue = ($revenue > (LIMIT_CUP - $revenueabout)) ? (LIMIT_CUP - $revenueabout) : $revenue;

        return [
            "user" => $this->GetUser($id),
            "percent" => $percent,
            "money" => $revenue,
            "moneypercent" => (int)(($revenue * $percent) / 100)
        ];
    }

    function CreateShareAgency($user, $money, $percent)
    {
        return [
            "user" => $user,
            "percent" => $percent,
            "money" => $money,
            "moneypercent" => (int)(($money * $percent) / 100)
        ];
    }

    public function CheckLevelId($linkid)
    {
        return sizeof(explode(" ", $linkid));
    }

  
    public function GetUser($id)
    {
        $param = [
            "select" => "user",
            "from" => "acc",
            "where" => "id='" . $id . "'"
        ];
        $db = new apps_libs_Dbconn();
        $result = $db->Select($param);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row ? $row["user"] : "";
        }
    }
    public function GetUserName($id)
    {
        $param = [
            "select" => "name",
            "from" => "detailacc",
            "where" => "idacc='" . $id . "'"
        ];
        $db = new apps_libs_Dbconn();
        $result = $db->Select($param);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row ? $row["name"] : "";
        }
    }
    public function GetDad($id)
    {
        $param = [
            "select" => "dadacc",
            "from" => "relationshipacc",
            "where" => "children='" . $id . "'"
        ];
        $db = new apps_libs_Dbconn();
        $result = $db->Select($param);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row ? $row["dadacc"] : null;
        }
    }
}

?>