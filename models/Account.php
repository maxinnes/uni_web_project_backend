<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/includes/DatabaseConnection.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/EmailVerification.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Exceptions/UserDoesNotExistException.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Exceptions/UserAlreadyExistsException.php';

class Account{
    // Account props
    public $id;
    private $created;
    private $updated;
    private $firstName;
    private $lastName;
    private $email;
    private $hash;
    //private $salt;
    private $passwordLastChanged;

    // DB details
    private const TABLE_PRIMARY_KEY = "AccountId";
    private const TABLE = "Accounts";

    public function __construct($idParse){
        $connection = new DatabaseConnection();
        $dbUser = $connection->getOneRecordById($this::TABLE,$this::TABLE_PRIMARY_KEY,$idParse);

        if($dbUser==NULL){
            throw new UserDoesNotExistException();
        }

        $this->id = $dbUser["AccountId"];
        $this->created = $dbUser["Created"];
        $this->updated = $dbUser["Updated"];
        $this->firstName = $dbUser["First Name"];
        $this->lastName = $dbUser["Last Name"];
        $this->email = $dbUser["Email"];
        $this->hash = $dbUser["Hash"];
        $this->passwordLastChanged = $dbUser["Password Last Changed"];
    }

    public static function getAccountViaEmail($email): Account{
        $connection = new DatabaseConnection();
        $result = $connection->getOneRecordByAttribute(Account::TABLE,"Email",$email);
        if($result==NULL){
            throw new UserDoesNotExistException();
        }else{
            $dbId = $result["AccountId"];
            return new Account($dbId);
        }
    }

    public function verifyPassword($password): bool{
        return password_verify($password,$this->hash);
    }

    public static function createNewAccount($firstName,$lastName,$email,$password){
        $connection = new DatabaseConnection();
        $hash = password_hash($password,PASSWORD_DEFAULT);

        $attributesAndValues = array(
            "First Name"=>$firstName,
            "Last Name"=>$lastName,
            "Email"=>$email,
            "Hash"=>$hash
        );

        try {
            $newAccountId = $connection->createNewRecord(Account::TABLE, $attributesAndValues);
            $newAccount = new Account($newAccountId);
            $newEmailValidationRecord = EmailVerification::createEmailValidationRecord($newAccount->id);
            $newEmailValidationRecord->sendValidationEmail($email);
            return $newAccount;
        } catch(PDOException $e){
            throw new UserAlreadyExistsException($e->getMessage());
        }
    }

    public function returnAsAssocArray(){
        return array(
            "accountId"=>$this->id,
            "firstName"=>$this->firstName,
            "lastName"=>$this->lastName,
            "email"=>$this->email
        );
    }
}