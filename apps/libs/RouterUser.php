<?php
    class apps_libs_RouterUser
    {
        public function apps_libs_RouterUser()
        {

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
                default:
            }
            switch($p)
            {
                case 'create':
                    $result.='create/create.php';
                    break;
                case 'chagepass':
                    $result.='chagepass/chagepass.php';
                    break;
                case 'chagedetail':
                    $result.='chagedetail/chagedetail.php';
                    break;
                case 'calacc':
                    $result.='calacc/calacc.php';
                    break;
                case 'staintroduce':
                    $result.='staintroduce/calacc.php';
                    break;
                case 'relationshipacc':
                    $result.='relationshipacc/showrelationshipacc.php';
                    break;
                case 'returnagency':
                    $result.='returnagency/returnagency.php';
                    break;
                case 'agency':
                    $result.='agency/agency.php';
                    break;
                case 'gioithieu':
                    $result.='agency/nguoigioithieu.php';
                    break;

                default:
            }
            return $result;
        }
    }

?>