<?php
//include_once("../bootstrap.php");
//include_once("../libs/Dbconn.php");
class apps_calculate_calculate
{
    public function apps_calculate_calculate()
    {

    }
    public function GetAPL($time)
    {
        $db = new apps_libs_Dbconn();
        $param = [
            "select" => "accumulate,percentcompanyreturn,limitshare",
            "from" => "setting",
            "where" => "id=1"
        ];
        $result = $db->SelectOne($param);

        $param2=[
            "select"=>"limitshare",
            "from"=>"settingshare",
            "where"=>"month(timecreate)=".$time["month"]." and year(timecreate)=".$time["year"]
        ];
        $result2=$db->SelectOne($param2);
        $row2=mysqli_fetch_assoc($result2);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $data = [
                "accumulate" => $row["accumulate"],
                "percentcompanyreturn" => $row["percentcompanyreturn"],
                "limitshare" => $row2["limitshare"]?$row2["limitshare"]:$row["limitshare"]
            ];
            return $data;
        } else {
            $data = [
                "accumulate" => "",
                "percentcompanyreturn" => "",
                "limitshare" => ""
            ];
            return $data;
        }
    }
    public function GetFLN()
    {
        $db = new apps_libs_Dbconn();

        $param = [
            "select" => "firstreturnshare,nextreturnshare,limitreturnshare",
            "from" => "setting",
            "where" => "id=1"
        ];
        $result = $db->SelectOne($param);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $data = [
                "firstreturnshare" => $row["firstreturnshare"],
                "nextreturnshare" => $row["nextreturnshare"],
                "limitreturnshare" => $row["limitreturnshare"]
            ];
            return $data;
        } else {
            $data = [
                "firstreturnshare" => "",
                "nextreturnshare" => "",
                "limitreturnshare" => ""
            ];
            return $data;
        }
    }
    public function GetLL()
    {
        $db = new apps_libs_Dbconn();

        $param = [
            "select" => "levelf1return,levelf2f5return",
            "from" => "setting",
            "where" => "id=1"
        ];
        $result = $db->SelectOne($param);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $data = [
                "levelf1return" => $row["levelf1return"],
                "levelf2f5return" => $row["levelf2f5return"]
            ];
            return $data;
        } else {
            $data = [
                "levelf1return" => "",
                "levelf2f5return" => ""
            ];
            return $data;
        }
    }
    public function GetEMPP()
    {
        $db = new apps_libs_Dbconn();

        $param = [
            "select" => "extractmax",
            "from" => "setting",
            "where" => "id=1"
        ];
        $result = $db->SelectOne($param);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $data = [
                "extractmax" => $row["extractmax"],
                "list" => []
            ];
            $param2 = [
                "select" => "money,percentsend",
                "from" => "settingreceived",
                "where" => "1 ORDER BY money ASC "
            ];

            $result2 = $db->Select($param2);

            $i = 0;
            if ($result2)
                while ($row2 = mysqli_fetch_assoc($result2)) {
                $data["list"][$i] = [
                    "money" => $row2["money"],
                    "percentsend" => $row2["percentsend"]
                ];
                $i++;
            }

            return $data;
        } else {
            $data = [
                "extractmax" => "",
                "list" => []
            ];
            return $data;
        }
    }

    public function CalValueShare($time,$check=false) // tính giá trị đơn vị đồng chia
    {
        if($check) 
        {
            $uti=new apps_libs_Utilities();
            $time_now=$uti->GetMonthNow();
            if(!$this->CheckShowShare()&&$time_now["month"]==$time["month"]&&$time_now["year"]==$time["year"]) 
                return 0;
        }

        $apl=$this->GetAPL($time);
        $revenue=$this->CalRevenue($time);
        $share=$this->CalTotalShare($time);
        if($share!=0)
        {
            $result=(int)((($revenue*$apl["percentcompanyreturn"])/100)/$share);

            return $result<$apl["limitshare"]?$result:$apl["limitshare"];
        }
        return 0;
    }
    public function CheckShowShare()
    {
        $param=[
            "select"=>"active",
            "from"=>"settingshowshare",
            "where"=>"month(timecreate)=month(NOW()) and year(timecreate)=year(NOW())"
        ];
        $db=new apps_libs_Dbconn();
        $result=$db->SelectOne($param);
        if($result)
        {
            $row=mysqli_fetch_assoc($result);
            if($row)
                if($row["active"]=="no") return false;
                else return true;
            else return false;
        }return false;
    }
    public function CalRevenue($time)// tổng doanh số một tháng
    {
        /*
            SELECT SUM(listproduct.price*listproduct.quantity) as total
            FROM historyproduct,listproduct
            WHERE listproduct.idhp=historyproduct.id and month(historyproduct.timecreate)=2 and year(historyproduct.timecreate)=2018
        */
        $param=[
            "select" => "SUM(listproduct.price*listproduct.quantity) as total",
            "from"=>"historyproduct,listproduct",
            "where"=> "listproduct.idhp=historyproduct.id and month(historyproduct.timecreate)=".$time["month"]." and year(historyproduct.timecreate)=".$time["year"]
        ];
        $db=new apps_libs_Dbconn();
        $result=$db->SelectOne($param);
        if($result)
        {
            $row=mysqli_fetch_assoc($result);
            return $row["total"]?$row["total"]:0;
        }
        else return 0;
    }
    public function CalTotalShare($time) // tổng số lượng đồng chia
    {   
        $apl=$this->GetAPL($time);
        $param=[
            "select"=>"SUM(FLOOR(price.total/".$apl["accumulate"].")) as max",
            "from"=>"
            (SELECT acc.id,SUM(listproduct.price*listproduct.quantity) as total
            FROM historyproduct,listproduct,acc
            WHERE acc.id=historyproduct.idacc and listproduct.idhp=historyproduct.id and month(historyproduct.timecreate)=".$time["month"]." and year(historyproduct.timecreate)=".$time["year"]."
            GROUP BY acc.user) 
            as price,acc",
            "where"=>"price.total >= ".$apl["accumulate"]." and acc.id= price.id"
        ];

        $db=new apps_libs_Dbconn();
        $result=$db->Select($param);
        if($result)
        {
            $row=mysqli_fetch_assoc($result);
            return $row["max"]?$row["max"]:0;
        }
        return 0;
    }
    public function AmountIncurred($time) // số lương tài khoản phát sinh
    {
        /*
            SELECT COUNT(incurred.user) as amount
            FROM 
            (SELECT acc.user
            FROM detailacc,acc,listproduct,historyproduct
            WHERE detailacc.idacc=acc.id and acc.type!='admin' and acc.id= historyproduct.idacc and historyproduct.id = listproduct.idhp and MONTH(historyproduct.timecreate)=2 and YEAR(historyproduct.timecreate)=2018 GROUP BY acc.user ORDER BY SUM(listproduct.price * listproduct.quantity) DESC) as incurred
        */
        $param=[
            "select"=>"COUNT(incurred.user) as amount",
            "from"=>"(SELECT acc.user
            FROM detailacc,acc,listproduct,historyproduct
            WHERE detailacc.idacc=acc.id and acc.type='user' and acc.id= historyproduct.idacc and historyproduct.id = listproduct.idhp and MONTH(historyproduct.timecreate)=".$time["month"]." and YEAR(historyproduct.timecreate)=".$time["year"]." GROUP BY acc.user ORDER BY SUM(listproduct.price * listproduct.quantity) DESC) as incurred"

        ];
        $db=new apps_libs_Dbconn();
        $result=$db->Select($param);
        if($result)
        {
            $row=mysqli_fetch_assoc($result);
            return $row["amount"]?$row["amount"]:0;
        }
        else return 0;
    }
    public function NoAmountIncurred($time) // số lương tài khoản không phát sinh
    {
        $number=$this->AmountIncurred($time);
        $param=[
            "select"=>"COUNT(*) as amount",
            "from"=>"acc",
            "where"=>"acc.type='user' and month(timecreate)<=".$time['month']." and year(timecreate)<=".$time["year"]
        ];
        $db=new apps_libs_Dbconn();
        $result=$db->Select($param);
        if($result)
        {
            $row=mysqli_fetch_assoc($result);
            return ((int)$row["amount"])-((int)$number);
        }
        else return 0;
    }

    public function GetPercent($money)
    {
        $param=[
            "select"=>"percentsend",
            "from"=>"settingreceived",
            "where"=>"money<=".$money." ORDER BY money DESC"
        ];
        $db=new apps_libs_Dbconn();
        $result=$db->SelectOne($param);
        $row=mysqli_fetch_assoc($result);
        if($row)
        {
            $empp=$this->GetEMPP();
            if($row["percentsend"]>$empp["extractmax"])
            {
                return $empp["extractmax"];
            }else return $row["percentsend"];
        }
        else 
        {
            return 0;
        }
    }
}

?>