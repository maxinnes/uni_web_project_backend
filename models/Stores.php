<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/includes/DatabaseConnection.php';

class Stores{

    public $id;
    private $storeName;
    private $created;
    private $accountId;

    private const TABLE_PRIMARY_KEY = "StoreId";
    private const TABLE = "Stores";

    public function __construct($id){
        $connection = new DatabaseConnection();
        $dbRecord = $connection->getOneRecordById($this::TABLE,$this::TABLE_PRIMARY_KEY,$id);

        if($dbRecord==null){
            throw new Exception("Store does not exist");
        }

        $this->id = $dbRecord["StoreId"];
        $this->storeName = $dbRecord["StoreName"];
        $this->created = $dbRecord["Created"];
        $this->accountId = $dbRecord["AccountId"];
    }

    public static function createNewStore($storeName,$accountId){
        $connection = new DatabaseConnection();

        $attributesAndValues =  array(
            "StoreName"=>$storeName,
            "AccountId"=>$accountId
        );

        $newRecordId = $connection->createNewRecord(Stores::TABLE,$attributesAndValues);
        return new Stores($newRecordId);
    }

    public static function getStoresByAccountId($id){
        $connection = new DatabaseConnection();
        $dbRecords = $connection->getMultipleRecordsByAttribute(Stores::TABLE,"AccountId",$id);

        $returnArray = array();
        foreach($dbRecords as $dbRecord){
            $storeObj = new Stores($dbRecord["StoreId"]);
            $returnArray[] = $storeObj;
        }
        return $returnArray;
    }
    public function returnAsAssocArray(){
        return array(
            "storeId"=>$this->id,
            "storeName"=>$this->storeName,
            "created"=>$this->created,
            "accountId"=>$this->accountId
        );
    }
}