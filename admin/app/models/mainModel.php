<?php

namespace app\models;

use \PDO;
use \PDOException;

if (file_exists(__DIR__ . "/../../config/server.php")) {
    require_once(__DIR__ . "/../../config/server.php");
}

class mainModel
{
    private $server = DB_SERVER;
    private $db = DB_NAME;
    private $user = DB_USER;
    private $pass = DB_PASS;

    protected function connect()
    {
        try {
            $conection = new PDO('mysql:host=' . $this->server . ';dbname=' . $this->db, $this->user, $this->pass);
            $conection->query("set names utf8;");
            $conection->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
            $conection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            return $conection;
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            exit;
        }
    }

    /*----------  Run queries function ----------*/
    protected function runQuery($query)
    {
        $sql = $this->connect()->prepare($query);
        $sql->execute();
        return $sql;
    }
    /*--------- /Run queries function -----------*/
    /*----------   ----------*/

    /*---------- Save data   ----------*/
    protected function saveData($table, $data)
    {

        $query = "INSERT INTO $table (";

        $c = 0;

        foreach ($data as $key) {
            if ($c >= 1) {
                $query .= ",";
            }
            $query .= $key["name_field"];
            $c++;
        }

        $query .= ") Values (";

        $c = 0;

        foreach ($data as $key) {
            if ($c >= 1) {
                $query .= ",";
            }
            $query .= $key["marker_field"];
            $c++;
        }

        $query .= ")";
        $pdo = $this->connect();
        $sql = $pdo->prepare($query);

        foreach ($data as $key) {
            $sql->bindParam($key["marker_field"], $key["value_field"]);
        }

        $sql->execute();
        //$lastId = $->lastInsertId();
        if ($table == "products") {
            return [
                'sql' => $sql,
                'lastId' => $pdo->lastInsertId()
            ];
        } else {
            return $sql;
        }
        //


    }
    /*---------- /Save data ----------*/

    /*---------- update data   ----------*/
    protected function updateData($table, $data, $condition)
    {

        $query = "UPDATE $table SET ";
        $c = 0;
        foreach ($data as $key) {
            if ($c >= 1) {
                $query .= ",";
            }
            $query .= $key["name_field"] . "=" . $key["marker_field"];
            $c++;
        }

        $query .= " WHERE " . $condition["condition_field"] . "=" . $condition["condition_marker"];

        $sql = $this->connect()->prepare($query);

        foreach ($data as $key) {
            $sql->bindParam($key["marker_field"], $key["value_field"]);
        }

        $sql->bindParam($condition["condition_marker"], $condition["condition_value"]);

        $sql->execute();

        return $sql;
    }
    /*---------- /update data ----------*/

    /*---------- Clean script ----------*/
    protected function cleanString($string)
    {
        $words = [
            "<script>",
            "</script>",
            "<script src",
            "<script type=",
            "SELECT * FROM",
            "SELECT ",
            " SELECT ",
            "DELETE FROM",
            "INSERT INTO",
            "DROP TABLE",
            "DROP DATABASE",
            "TRUNCATE TABLE",
            "SHOW TABLES",
            "SHOW DATABASES",
            "<?php",
            "?>",
            "--",
            "^",
            "<",
            ">",
            "==",
            "=",
            ";",
            "::"
        ];
        $string = trim($string);
        $string = stripslashes($string);

        foreach ($words as $word) {
            $string = str_ireplace($word, "", $string);
        }
        $string = trim($string);
        $string = stripslashes($string);

        return $string;
    }
    /*---------- /Clean script ----------*/

    /*---------- Data verification ----------*/
    protected function verifyData($filter, $string)
    {
        if (preg_match("/^" . $filter . "$/u", $string)) {
            return false;
        } else {
            return true;
        }
    }
    /*---------- /Data verification ----------*/

    /*---------- Select data ----------*/
    protected function selectData($type, $table, $field, $id)
    {
        if ($type == "unique") {
            $sql = $this->connect()->prepare("SELECT * FROM $table WHERE $field=:ID");
            $sql->bindParam(":ID", $id);
        } elseif ($type == "normal") {
            $sql = $this->connect()->prepare("SELECT $field FROM $table");
        }
        $sql->execute();
        return $sql;
    }
    /*---------- /Select data ----------*/

    /*---------- Delate data ----------*/
    protected function delateData($table, $field, $id)
    {
        $sql = $this->connect()->prepare("DELETE FROM $table WHERE $field=:id");
        $sql->bindParam(":id", $id);
        $sql->execute();

        return $sql;
    }
    /*---------- /Delate data ----------*/
}
