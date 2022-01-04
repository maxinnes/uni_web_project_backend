<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/DatabaseConnection.php';

class OrderAddress{

    public $id;
    private $orderId;
    private $created;
    private $addressLineOne;
    private $addressLineTwo;
    private $addressLineThree;
    private $city;
    private $county;
    private $postcode;

    private const TABLE_PRIMARY_KEY = "AddressId";
    private const TABLE = "CustomerAddress";

    public function __construct($id){
        $connection = new DatabaseConnection();
        $dbRecord = $connection->getOneRecordById($this::TABLE, $this::TABLE_PRIMARY_KEY, $id);

        if ($dbRecord == null) {
            throw new Exception("Address does not exist");
        }

        $this->id = $dbRecord["AddressId"];
        $this->orderId = $dbRecord["OrderId"];
        $this->created = $dbRecord["Created"];
        $this->addressLineOne = $dbRecord["AddressLine1"];
        $this->addressLineTwo = $dbRecord["AddressLine2"];
        $this->addressLineThree = $dbRecord["AddressLine3"];
        $this->city = $dbRecord["City"];
        $this->county = $dbRecord["County"];
        $this->postcode = $dbRecord["Postcode"];
    }

    public static function createNewAddress($orderId, $addressLine1, $addressLine2, $addressLine3, $city, $county, $postcode){
        $connection = new DatabaseConnection();

        $attributesAndValues = array(
            "OrderId" => $orderId,
            "AddressLine1" => $addressLine1,
            "AddressLine2" => $addressLine2,
            "AddressLine3" => $addressLine3,
            "City" => $city,
            "County" => $county,
            "Postcode" => $postcode
        );

        try {
            $newRecordId = $connection->createNewRecord(OrderAddress::TABLE, $attributesAndValues);
            return new OrderAddress($newRecordId);
        } catch (PDOException $e) {
            throw new Exception("Could not create address record.");
        }

    }

    public static function getOrderAddressByOrderId($id){
        $connection = new DatabaseConnection();
        try {
            $dbRecord = $connection->getOneRecordByAttribute(OrderAddress::TABLE, "OrderId", $id);
            return new OrderAddress($dbRecord["AddressId"]);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function returnAsAssocArray(){
        return array(
            "addressId" => $this->id,
            "orderId" => $this->orderId,
            "created" => $this->created,
            "addressLineOne" => $this->addressLineOne,
            "addressLineTwo" => $this->addressLineTwo,
            "addressLineThree" => $this->addressLineThree,
            "city" => $this->city,
            "county" => $this->county,
            "postcode" => $this->postcode
        );
    }
}