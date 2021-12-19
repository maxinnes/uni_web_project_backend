<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/includes/DatabaseConnection.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Exceptions/UserHasSubscriptionException.php';

class Subscriptions{

    public $id;
    private $started;
    private $accountId;
    private $price;
    private $subscriptionChoice;
    private $subscriptionEnd;
    private $nextPaymentDate;

    private const TABLE_PRIMARY_KEY = "SubscriptionId";
    private const TABLE = "Subscriptions";

    public function __construct($idParse){
        $connection = new DatabaseConnection();
        $dbRecord = $connection->getOneRecordById($this::TABLE,$this::TABLE_PRIMARY_KEY,$idParse);

        if($dbRecord==null){
            throw new Exception("Could not find record");
        }

        $this->id = $dbRecord["SubscriptionId"];
        $this->started = $dbRecord["Started"];
        $this->accountId = $dbRecord["AccountId"];
        $this->price = $dbRecord["Price"];
        $this->subscriptionChoice = $dbRecord["SubscriptionChoice"];
        $this->subscriptionEnd = $dbRecord["SubscriptionEnd"];
        $this->nextPaymentDate = $dbRecord["NextPaymentDate"];
    }

    public static function doesUserHaveSubscription($accountId){
        $connection = new DatabaseConnection();

        $table = Subscriptions::TABLE;
        $sql = "SELECT * FROM `$table` WHERE `AccountId`=$accountId AND `SubscriptionEnd` IS NULL";
        $result = $connection->runCustomGetQuery($sql);

        if(count($result)===0){
            return false;
        }else{
            return true;
        }
    }

    public static function createNewSubscription($accountId,$price,$subscriptionChoice){
        $connection = new DatabaseConnection();

        if(!Subscriptions::doesUserHaveSubscription($accountId)) {

            $oneMonth = new DateInterval("P1M");
            $nextPaymentDate = date_add(new DateTime(), $oneMonth);

            $attributesAndValues = array(
                "AccountId" => $accountId,
                "Price" => $price,
                "SubscriptionChoice" => $subscriptionChoice,
                "NextPaymentDate" => $nextPaymentDate->format("Y-m-d")
            );

            $newRecordId = $connection->createNewRecord(Subscriptions::TABLE, $attributesAndValues);
            return new Subscriptions($newRecordId);
        }else{
            throw new UserHasSubscriptionException("User has a subscription");
        }
    }

}
