<?php

class DatabaseConnection
{
    protected $username = "gizaadmin";
    protected $password = "oL3d7CxQ33";

    private $conn = null;

    function connectDB(){

        try {
            $this->conn = new PDO('mysql:host=mysql.gizapeaks.com;dbname=gizapeaks', $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            $dbh = null;

            die ("Major Error" . $e->getMessage());
        }
       return $this->conn;
    } 
}
?>