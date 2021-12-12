<?php
// TODO Should make the connection outside this script, Because everytime a new db connection is called, it reconnects
class DatabaseConnection{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $connection;
    // TODO Need a update record method
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

    public function getOneRecordById($table,$recordPrimaryKeyName, $recordId){ // TODO Make sure to error handle this
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
}