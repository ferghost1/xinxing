<?php
    try
    {
        include_once ('Config.php');
    }
    catch(Exception $ex)
    {

    }
    class apps_libs_Dbconn
    {
        private $user;
        private $pass;
        private $host;
        private $database;

        public $queryParam;

        private $conn;
        /*public function apps_libs_Dbconn($user,$pass,$host,$database)
        {
            $this->user=$user;
            $this->pass=$pass;
            $this->host=$host;
            $this->database=$database;

            $conn=NULL;
        }*/

        public function apps_libs_Dbconn()
        {
            $data=new apps_libs_Config();
            $this->user=$data->GetUser();
            $this->pass=$data->GetPass();
            $this->host=$data->GetHost();
            $this->database=$data->GetDatabase();
        }

        public function Connect()
        {
            if($this->conn==NULL)
                $this->conn=mysqli_connect($this->host,$this->user,$this->pass,$this->database) or die('Loi ket noi');
        }

        public function DisConnect()
        {
            if($this->conn!=null)
            {
                mysqli_close($this->conn);
                $this->conn=NULL;
            }
        }
        public function BuildParams($params)
        {
            $this->queryParam=NULL;
            $default=[
                "select"=>"*",
                "from"=>"",
                "where"=>"",
                "limit"=>"",
                "param"=>""
            ];
            $this->queryParam=array_merge($default,$params);
            return $this;
        }
        public function transaction($type){
            switch ($type) {
                case 'autocommit':
                    $this->Connect();
                    mysqli_autocommit($this->conn,false);
                    break;
                case 'commit':
                    mysqli_commit($this->conn);
                    $this->DisConnect();
                    break;
                case 'rollback':
                    mysqli_rollback($this->conn);
                    $this->DisConnect();
                    break;    
            }
        }
        public function trans_query($sql,$single = false){
                $result=mysqli_query($this->conn,$sql);
                if(!$result || !$result->num_rows){
                    throw new Exception("Có lỗi dữ liệu".mysqli_error($this->conn));
                    return;
                }
                while ($row = mysqli_fetch_object($result))
                {
                    $res[] = $row;
                }
                if($single)
                    return $res[0];
                return $res;
        }
        public function trans_voidQuery($sql){
                mysqli_query($this->conn,$sql);
                if(!mysqli_error($this->conn))
                {
                    return true;
                }
                throw new Exception("Error in voidQuery: ".mysqli_error($this->conn));
                return false;
        }
        public function query($sql,$single = false){
                $this->Connect();

                $result=mysqli_query($this->conn,$sql);
                if(!$result || !$result->num_rows){
                    $this->DisConnect();
                    // return;
                    return array();
                }
                while ($row = mysqli_fetch_object($result))
                {
                    $res[] = $row;
                }
                $this->DisConnect();
                if($single)
                    return $res[0];
                return $res;
        }
        public function voidQuery($sql){
                $this->Connect();
                mysqli_query($this->conn,$sql);
                if(!mysqli_error($this->conn) && mysqli_affected_rows($this->conn))
                {
                    $this->DisConnect();
                    return true;
                }

                $this->DisConnect();
                return false;
        }
        // $is_trans Chọn sử dụng transaction
        public function Querry($query, $is_trans = false)
        {
            //echo $query."<br/>";
            if($this->conn==NULL || $is_trans)
            {
                $this->Connect();
                $result=mysqli_query($this->conn,$query);
                // Nếu đang trong transaction mà lỗi sql thì throw except
                if($is_trans){
                    if(mysqli_error($this->conn) && !mysqli_affected_rows($this->conn)){
                        throw new Exception("Error Querry: ".mysqli_error($this->conn));
                    }
                }else{
                    $this->DisConnect();
                }
                return $result;
            }
            return NULL;
        }
        
        public function Select($params,$return=NULL,$is_trans = false)
        {
            $this->BuildParams($params);
            $query= "select ".$this->queryParam["select"]." from ".$this->queryParam["from"];
            if($this->queryParam["where"])$query.=" where ".$this->queryParam["where"];
            if($this->queryParam["limit"])$query.=" limit ".$this->queryParam["limit"];
            
            //echo $query ."<br/>";
            if(!$return){
                // die($query);
                return $this->Querry($query,$is_trans);
            }
            else return $query;
        }

        public function SelectOne($params,$return=NULL, $is_trans = false)
        {
            $params["limit"]="1";
            return $this->Select($params, $return, $is_trans);
        }

        public function Delete($params,$return=NULL,$is_trans = false)
        {
            $this->BuildParams($params);
            $query="delete from ".$this->queryParam["from"];
            if($this->queryParam["where"]) $query.=" where ".$this->queryParam["where"];
            else return NULL;
            //echo $query;
            if(!$return)
                return $this->Querry($query,$is_trans);
            return $query;
        }

        public function Insert($params,$return=NULL,$is_trans = false)
        {
            $this->BuildParams($params);
            $query="insert into ".$this->queryParam["from"];
            $query.="(".$params["param"]["col"];
            $query.=") values (";
            //var_dump( $params["param"]["data"]);
            
            foreach($params["param"]["data"] as $item)
            {
                $query.=$item.",";
            }
            
            $query=trim($query,",");
            $query.=")";

            //echo $query;
            if(!$return)
                return $this->Querry($query,$is_trans);
            else return $query;
        }

        public function Update($params,$return=NULL,$is_trans = false)
        {
            $this->BuildParams($params);
            $query="update ".$this->queryParam["from"]." set ";
            $dem=0;
            foreach($this->queryParam['param']['col'] as $item)
            {
                $query.=$item.'='.$params['param']['data'][$dem].',';
                $dem++;
            }
            $query=trim($query,",");
            if($this->queryParam['where'])
                $query.=" where ".$this->queryParam['where'];
                
            if(!$return)
                return $this->Querry($query,$is_trans);
            else return $query;
        }

        public function CreateID($table,$nameCl,$is_trans = false)
        {
            $id='';
            $result=NUlL;
            $row=NULL;
            $chars = 'ABCD0123456789';
            do
            {//strlen($chars)
                $id='';
                for($i=0;$i<30;$i++) $id.=$chars[rand(0,strlen($chars)-1)];
                $query=[
                    "from"=>$table,
                    "select"=>$nameCl,
                    "where"=>$nameCl.'='."'".$id."'"
                ];
                $result=$this->SelectOne($query,'',$is_trans);
                $row=mysqli_fetch_assoc($result);

            }while($row!=NULL);
            //echo $id;
            return $id;
        }

        public function CheackValue($table,$nameCl,$valueCl, $is_trans = false)
        {
            $param=[
                "select"=>$nameCl,
                "from"=>$table,
                "where"=>$nameCl."=".$valueCl
                ];
            $result=$this->SelectOne($param);
            $row=mysqli_fetch_assoc($result);
            if(isset($row[$nameCl]))return true;
                else return false;
        }

        public function CheackTable($name, $is_trans = false)
        {
            $query='select * from '.$name;
            $result=$this->Querry($query, $is_trans);
            if($result) return true;
            return false;
        }
        public function GetValue($table,$nameCl,$where)
        {
            $param=[
                "select"=>$nameCl,
                "from"=>$table,
                "where"=>$where
            ];
            //echo $table;
            $result=$this->SelectOne($param);
            if($result)return mysqli_fetch_assoc($result)[$nameCl];
            return NULL;
        }
    }
?>