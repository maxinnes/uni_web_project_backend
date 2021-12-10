<?php

class DatabaseConnection{
    public $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $connection;

    public function __construct(){
        try {
            $conn = new PDO("mysql:host=$this->servername;dbname=mercator", $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
            $conn->setAttribute(PDO::ATTR_PERSISTENT,true);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection = $conn;
        }
        catch (PDOException $e) {
            //echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getOneRecordById($table, $recordId){ // TODO Make sure to error handle this
        $sql = "SELECT * FROM `$table` WHERE `AccountId` = $recordId;";
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        return $statement->fetch();
    }

    public function getOneRecordByAttribute($table,$attributeName,$attributeValue){
        $sql = "SELECT * FROM `$table` WHERE `$attributeName` = '$attributeValue'";
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        return $statement->fetch();
    }

    public function createNewRecord($table,$attributesAndValues) { // TODO Make sure to error handle this
        $query = "INSERT INTO `$table` (";
        foreach(array_keys($attributesAndValues) as $array_key){
            $query = $query."`".$array_key."`,";
        }
        $query = rtrim($query,",").") VALUES (";
        foreach(array_values($attributesAndValues) as $array_value){
            $query = $query."'".$array_value."',";
        }
        $query = rtrim($query,",").")";

        $statement = $this->connection->prepare($query);
        $statement->execute();

        //return true; // TODO If successful, should return the ID of the new record
        //return $statement->fetch();
    }
}