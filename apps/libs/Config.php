<?php
    class apps_libs_Config
    {
        private $user;
        private $pass;
        private $host;
        private $database;
        
        function apps_libs_Config()
        {
            $this->user='root';
            $this->pass='';
            $this->host='127.0.0.1';
            $this->database='xixinin_mbid';
        }

        function GetUser()
        {
            return $this->user;
        }

        function GetPass()
        {
            return $this->pass;
        }

        function GetHost()
        {
            return $this->host;
        }

        function GetDatabase()
        {
            return $this->database;
        }
    }
?>