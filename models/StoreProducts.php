<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/includes/DatabaseConnection.php';

class StoreProducts{
    public $id;
    private $storeId;
    private $name;
    private $description;
    private $image;
    private $price;

    private const TABLE_PRIMARY_KEY = "ProductId";
    private const TABLE = "StoreProducts";

    public function __construct($id){
        $connection = new DatabaseConnection();
        $dbRecord = $connection->getOneRecordById($this::TABLE,$this::TABLE_PRIMARY_KEY,$id);

        if($dbRecord===null){
            throw new Exception("Product does not exist");
        }

        $this->id = $dbRecord["ProductId"];
        $this->storeId = $dbRecord["StoreId"];
        $this->name = $dbRecord["Name"];
        $this->description = $dbRecord["Description"];
        $this->image = $dbRecord["Image"];
        $this->price = $dbRecord["Price"];
    }
    public static function createNewProduct($storeId,$name,$description,$image,$price){
        $connection = new DatabaseConnection();

        $attributesAndValues = array(
            "StoreId"=>$storeId,
            "Name"=>$name,
            "Description"=>$description,
            "Image"=>$image,
            "Price"=>$price
        );

        $newRecordId = $connection->createNewRecord(StoreProducts::TABLE,$attributesAndValues);
        return new StoreProducts($newRecordId);
    }
    public static function getProductsByStoreId($storeId){
        $connection = new DatabaseConnection();
        $dbRecords = $connection->getMultipleRecordsByAttribute(StoreProducts::TABLE,"StoreId",$storeId);

        $returnArray = array();
        foreach($dbRecords as $dbRecord){
            $returnArray[] = new StoreProducts($dbRecord["ProductId"]);
        }

        return $returnArray;
    }
    public function returnAsAssocArray(){
        return array(
            "productId"=>$this->id,
            "storeId"=>$this->storeId,
            "name"=>$this->name,
            "description"=>$this->description,
            "image"=>$this->image,
            "price"=>$this->price
        );
    }
}