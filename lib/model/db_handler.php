<?php

require_once("lib/db_config.php");

class DBHandler{

    private $conn;

    public function __construct(){

        try{

            $this->conn = new PDO("mysql:" . $GLOBALS["host"] . ";dbname=" . $GLOBALS["dbname"] . ";charset=utf8", $GLOBALS["username"], $GLOBALS["pwd"]);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        }catch(PDOException $pdoe){
    
            echo $pdoe->getMessage();
    
        }

    }

    public function get_connection(): PDO{

        return $this->conn;

    }

    public function __destruct(){

        $this->conn = NULL;

    }

}

?>