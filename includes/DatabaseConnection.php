<?php

class DatabaseConnection{
    private $servername = "80.82.113.174";
    private $username = "user1";
    private $password = "Simple_123";
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

    public function getOneRecordById($table,$recordPrimaryKeyName, $recordId){
        $sql = "SELECT * FROM `$table` WHERE `$recordPrimaryKeyName` = $recordId;";
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

    public function getMultipleRecordsByAttribute($table,$attributeName,$attributeValue){
        $sql = "SELECT * FROM `$table` WHERE `$attributeName` = '$attributeValue'";
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function createNewRecord($table,$attributesAndValues) {
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
        return $this->connection->lastInsertId();
    }
    public function updateRecord($table,$recordPrimaryKeyName,$recordPrimaryKeyValue,$attributesAndValues){
        $query = "UPDATE `$table` SET";
        foreach($attributesAndValues as $attribute=>$value){
            $query = $query." `$attribute` = '$value',";
        }
        $query = rtrim($query,",")." WHERE `$table`.`$recordPrimaryKeyName` = $recordPrimaryKeyValue;";

        $statement = $this->connection->prepare($query);
        $statement->execute();
    }
    public function deleteRecordById($table,$recordPrimaryKeyName,$recordPrimaryKeyValue){
        $query = "DELETE FROM `$table` WHERE `$table`.`$recordPrimaryKeyName` = $recordPrimaryKeyValue";
        $statement = $this->connection->prepare($query);
        $statement->execute();
    }
    public function runCustomGetQuery($query){
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }
}