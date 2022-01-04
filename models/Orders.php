<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/includes/DatabaseConnection.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Stores.php';

class Orders{
    // Order props
    public $id;
    private $createdDate;
    private $totalPrice;
    private $purchasedProducts;
    private $storeId;
    private $customerEmail;
    private $status;

    // DB Details
    private const TABLE_PRIMARY_KEY = "OrderId";
    private const TABLE = "Orders";

    public function __construct($id){
        $connection = new DatabaseConnection();
        $dbRecord = $connection->getOneRecordById($this::TABLE,$this::TABLE_PRIMARY_KEY,$id);

        if($dbRecord===null){
            throw new Exception("Order does not exist");
        }

        $this->id = $dbRecord[$this::TABLE_PRIMARY_KEY];
        $this->createdDate = $dbRecord["Created Date"];
        $this->totalPrice = $dbRecord["Total Price"];
        $this->purchasedProducts = $dbRecord["Purchased Products"];
        $this->storeId = $dbRecord["Store Id"];
        $this->customerEmail = $dbRecord["Customer Email"];
        $this->status = $dbRecord["Status"];

    }

    public static function createNewOrder($totalPrice,$purchasedProducts,$storeId,$customerEmail){
        $connection = new DatabaseConnection();

        $attributesAndValues = array(
            "Total Price"=>$totalPrice,
            "Purchased Products"=>json_encode($purchasedProducts),
            "Store Id"=>$storeId,
            "Customer Email"=>$customerEmail,
            "Status"=>"In Progress"
        );

        $newRecordId = $connection->createNewRecord(Orders::TABLE,$attributesAndValues);
        self::sendEmailReceipt($customerEmail,$storeId,$totalPrice,$purchasedProducts);
        return new Orders($newRecordId);
    }

    public static function sendEmailReceipt($email,$storeId,$orderTotal,$orderPurchasedProducts){
        $storeObj = new Stores($storeId);
        $storeObjArr = $storeObj->returnAsAssocArray();
        $storeName = $storeObjArr["storeName"];

        $messageContents = "Thank for your order from $storeName, \n";
        foreach($orderPurchasedProducts as $orderPurchasedProduct){
            $productName = $orderPurchasedProduct["name"];
            $productQuantity = $orderPurchasedProduct["quantity"];
            $productTotal = $orderPurchasedProduct["total"];
            $messageContents = $messageContents."\n$productName x $productQuantity: £$productTotal";
        }
        $messageContents = $messageContents."\n\nTotal: £$orderTotal";

        mail($email,"Your order from: $storeName",$messageContents);
    }

    public static function getAllOrdersByStoreId($id){
        $connection = new DatabaseConnection();
        $dbRecords = $connection->getMultipleRecordsByAttribute(Orders::TABLE,"Store Id",$id);
        $returnArray = array();
        foreach($dbRecords as $dbRecord){
            $returnArray[] = new Orders($dbRecord[Orders::TABLE_PRIMARY_KEY]);
        }
        return $returnArray;
    }

    public function returnAsAssocArray(){
        return array(
            "orderId"=>$this->id,
            "createdDate"=>$this->createdDate,
            "totalPrice"=>$this->totalPrice,
            "purchasedProducts"=>$this->purchasedProducts,
            "storeId"=>$this->storeId,
            "customerEmail"=>$this->customerEmail,
            "status"=>$this->status
        );
    }

}