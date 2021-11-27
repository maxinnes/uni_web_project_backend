<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/includes/DatabaseConnection.php';

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
        $dbId = $result["AccountId"];
        return new Account($dbId);
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
        $connection->createNewRecord(Account::TABLE,$attributesAndValues);
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