<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/includes/DatabaseConnection.php';
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
    private $password;
    private $salt;
    private $passwordLastChanged;

    // DB details
    private const TABLE = "Accounts";

    public function __construct($idParse){
        $connection = new DatabaseConnection();
        $dbUser = $connection->getOneRecordById($this::TABLE,$idParse);

        if($dbUser==NULL){
            throw new UserDoesNotExistException();
        }

        $this->id = $dbUser["AccountId"];
        $this->created = $dbUser["Created"];
        $this->updated = $dbUser["Updated"];
        $this->firstName = $dbUser["First Name"];
        $this->lastName = $dbUser["Last Name"];
        $this->email = $dbUser["Email"];
        $this->password = $dbUser["Password"];
        $this->salt = $dbUser["Salt"];
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
        $salt = base64_decode($this->salt);
        $hash = hash_pbkdf2("sha256",$password,$salt,1000,20);
        if($hash==$this->password){
            return true;
        }else{
            return false;
        }
    }

    public static function createNewAccount($firstName,$lastName,$email,$password){
        $connection = new DatabaseConnection();
        // TODO Need to change password algorithm to password_verify
        $salt = openssl_random_pseudo_bytes(16);
        $hash = hash_pbkdf2("sha256", $password, $salt, 1000, 20);
        $salt = base64_encode($salt);

        $attributesAndValues = array(
            "First Name"=>$firstName,
            "Last Name"=>$lastName,
            "Email"=>$email,
            "Password"=>$hash,
            "Salt"=>$salt
        );
        try {
            $connection->createNewRecord(Account::TABLE, $attributesAndValues);
            $newAccount = Account::getAccountViaEmail($email);
            EmailVerification::createEmailValidationRecord($newAccount->id,$email);
            return $newAccount;
        } catch(PDOException $e){
            throw new UserAlreadyExistsException("User with this email already exists.");
        }
    }

    public function getFirstName(){
        return $this->firstName;
    }

    public function getLastName(){
        return $this->lastName;
    }

    public function getEmail()
    {
        return $this->email;
    }
}