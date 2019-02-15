<?php
    class apps_libs_Router
    {
        public function Router()
        {
        }

        public function LoginPage()
        {
            //$link= $_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/DaCap/login.php";
            //header('Location: '.$link);
            header('Location: /login.php');
        }

        public function LogoutPage()
        {
            //$link= $_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/DaCap/logout.php";
            //header('Location: '.$link);
            header('Location: /logout.php');
        }

        public function AdminPage()
        {
            //$link= $_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/DaCap/admin/index.php";
            //header('Location: '.$link);
            header('Location: /admin');
        }

        public function UserPage()
        {
            //$link= $_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/DaCap/admin/index.php";
            //header('Location: '.$link);
            header('Location: /user');
        }
        public function GetLinkImg($img=NULL)
        {
            //return $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.$img;
            if($img=="") $img = 'admin/img/avata/default.png';
            /*if(count(explode('admin/',$img))>1)
                return explode('admin/',$img)[1];
            else return"";*/
            return "xinxing/".$img;
        }
        public function GetGet($name=NULL)
        {
            if($name!=NULL)
            {
                return isset($_GET[$name]) ? $_GET[$name] : NULL;
            }
            return $_GET;
        }

        public function GetPost($name=NULL)
        {
            if($name!=NULL)
            {
                return isset($_POST[$name]) ? $_POST[$name] : NULL;
            }
            return $_POST;
        }
        public function GetNameOnline()
        {
            $db=new apps_libs_Dbconn();
            $rt=new apps_libs_Router();
            $user=new apps_libs_UserLogin();
            $id=$user->GetAcc();
            if($id)
            {
                $param=[
                    "select"=>"name",
                    "from"=>"detailacc",
                    "where"=>"idacc='".$id."'"
                ];
                $result=$db->SelectOne($param);
                $row=mysqli_fetch_assoc($result);
                return $row['name'];
            }
            return null;
        }
        public function GetLink()
        {
            return $_SERVER['DOCUMENT_ROOT'];//up len host sua lai cai nay
        }

        public function GetLinkMenuSlug()
        {
            $link=$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            return explode('?', $link)[0];
        }
        public function DeleteFileOnPath($path) {
            $path = rtrim($path, '/') . '/';
            $handle = opendir($path);
           
            while (false !== ($file = readdir($handle))) {
              if($file != '.' and $file != '..' ) {
                $fullpath = $path.$file;
                if (is_dir($fullpath)) rmdir_recurse($fullpath);
                else unlink($fullpath);
              }
            }
            closedir($handle);
          }
        public function GetFile($r,$p)
        {
            $result='';
            switch($r)
            {
                case 'acc':
                    $result.='acc/';
                    break;
                case 'total':
                    $result.='total/';
                    break;
                case 'setting':
                    $result.='setting/';
                    break;
                default:
            }
            switch($p)
            {
                case 'create':
                    $result.='create/create.php';
                    break;
                case 'show':
                    $result.='list/show.php';
                    break;
                case 'chosetimeshow':
                    $result.='chosetimeshow/chosetimeshow.php';
                    break;
                case 'listconstant':
                    $result.='listconstant/show.php';
                    break;
                case 'showadmin':
                    $result.='list/showadmin.php';
                    break;
                case 'detail':
                    $result.='detail/detail.php';
                    break;
                case 'menu':
                    $result.='menufunction/menu.php';
                    break;
                case 'relationshipacc':
                    $result.='relationshipacc/relationshipacc.php';
                    break;
                case 'showrelationshipacc':
                    $result.='relationshipacc/showrelationshipacc.php';
                    break;
                case 'product':
                    $result.='product/list.php';
                    break;
                case 'productcrt':
                    $result.='product/create.php';
                    break;
                case 'productcrt':
                    $result.='product/create.php';
                    break;
                case 'shows':
                    $result.='show/setting.php';
                    break;
                case 'settingshare':
                    $result.='share/settingshare.php';
                    break;
                case 'calacc':
                    $result.='calacc/calacc.php';
                    break;
                case 'chagepass':
                    $result.='chagepass/chagepass.php';
                    break;
                case 'chagedetail':
                    $result.='chagedetail/chagedetail.php';
                    break;
                case 'list':
                    $result.='agency/list.php';
                    break;
                case 'edit':
                    $result.='agency/edit.php';
                    break;
                default:
            }
            return $result;
        }
    }
?>