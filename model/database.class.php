<?php

class DatabaseConnection
{
    protected $username = "";
    protected $password = "";

    private $conn = null;

    function connectDB(){

        try {
            $this->conn = new PDO('mysql:host=;dbname=', $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            $dbh = null;

            die ("Major Error" . $e->getMessage());
        }
       return $this->conn;
    } 
}
?>