<?php

namespace app\models;

use \PDO;
use \PDOException; 

if (file_exists(__DIR__. "../../config/server.php")){
    require_once(__DIR__. "../../config/server.php");
}

class mainModel{
    private $server = DB_SERVER;
    private $db = DB_NAME;
    private $user = DB_USER;
    private $pass = DB_PASS;

    protected function conectar(){
        try{
            $conection = new PDO('mysql:host=' . $this->server . ';dbname=' .$this->db, $this->user, $this->pass);
            $conection->query("set names utf8;");
            $conection->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
            $conection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            return $conection;
        }catch(PDOException $e){
            echo "Error de conexión: ".$e->getMessage();
            exit;

    }
}



}