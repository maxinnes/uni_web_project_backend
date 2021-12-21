<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/includes/DatabaseConnection.php';

class AccountAddresses{

    public $id;
    private $accountId;
    private $created;
    private $addressLineOne;
    private $addressLineTwo;
    private $addressLineThree;
    private $city;
    private $county;
    private $postcode;

    private const TABLE_PRIMARY_KEY = "AddressId";
    private const TABLE = "AccountAddresses";

    public function __construct($id){
        $connection = new DatabaseConnection();
        $dbRecord = $connection->getOneRecordById($this::TABLE,$this::TABLE_PRIMARY_KEY,$id);

        if($dbRecord==null){
            throw new Exception("Address does not exist");
        }

        $this->id = $dbRecord["AddressId"];
        $this->accountId = $dbRecord["AccountId"];
        $this->created = $dbRecord["Created"];
        $this->addressLineOne = $dbRecord["AddressLine1"];
        $this->addressLineTwo = $dbRecord["AddressLine2"];
        $this->addressLineThree = $dbRecord["AddressLine3"];
        $this->city = $dbRecord["City"];
        $this->county = $dbRecord["County"];
        $this->postcode = $dbRecord["Postcode"];
    }
    public static function createNewAddress($accountId,$addressLine1,$addressLine2,$addressLine3,$city,$county,$postcode){
        $connection = new DatabaseConnection();

        $attributesAndValues = array(
            "AccountId"=>$accountId,
            "AddressLine1"=>$addressLine1,
            "AddressLine2"=>$addressLine2,
            "AddressLine3"=>$addressLine3,
            "City"=>$city,
            "County"=>$county,
            "Postcode"=>$postcode
        );

        try{
            $newRecordId = $connection->createNewRecord(AccountAddresses::TABLE,$attributesAndValues);
            return new AccountAddresses($newRecordId);
        }catch(PDOException $e){
            throw new Exception("Could not create address record.");
        }

    }
    public static function getAccountAddressByAccountId($id){
        $connection = new DatabaseConnection();
        try {
            $dbRecord = $connection->getOneRecordByAttribute(AccountAddresses::TABLE, "AccountId", $id);
            return new AccountAddresses($dbRecord["AddressId"]);
        } catch(PDOException $e){
            return null;
        }
    }
    public function returnAsAssocArray(){
        return array(
            "addressId"=>$this->id,
            "accountId"=>$this->accountId,
            "created"=>$this->created,
            "addressLineOne"=>$this->addressLineOne,
            "addressLineTwo"=>$this->addressLineTwo,
            "addressLineThree"=>$this->addressLineThree,
            "city"=>$this->city,
            "county"=>$this->county,
            "postcode"=>$this->postcode
        );
    }
}