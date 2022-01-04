<?php
// Imports
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/DatabaseConnection.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Exceptions/EmailValidationException.php';

class EmailVerification{

    public $id;
    private $accountId;
    private $isVerified;
    private $dateVerified;
    private $verificationCode;

    private const TABLE_PRIMARY_KEY = "EmailValidationId";
    private const TABLE = "EmailValidation";

    public function __construct($id){
        $connection = new DatabaseConnection();
        $validationRecord = $connection->getOneRecordById($this::TABLE,$this::TABLE_PRIMARY_KEY,$id);

        $this->id = $id;
        $this->accountId = $validationRecord['AccountId'];
        $this->isVerified = $validationRecord['IsVerified'];
        $this->dateVerified = $validationRecord['DateVerified'];
        $this->verificationCode = $validationRecord['VerificationCode'];
    }

    public static function getNewEmailVerificationByCode($verificationCode){
        $connection = new DatabaseConnection();
        try {
            $verificationRecord = $connection->getOneRecordByAttribute(EmailVerification::TABLE, "VerificationCode", $verificationCode);
            $id = $verificationRecord[EmailVerification::TABLE_PRIMARY_KEY];
            return new EmailVerification($id);
        } catch(PDOException $e){
            throw new EmailValidationException("Verification code does not exist");
        }
    }

    public static function getNewEmailVerificationByAccountId($accountId){
        $connection = new DatabaseConnection();
        $verificationRecord = $connection->getOneRecordByAttribute(EmailVerification::TABLE,"AccountId",$accountId);
        $id = $verificationRecord[EmailVerification::TABLE_PRIMARY_KEY];
        return new EmailVerification($id);
    }

    public function sendValidationEmail($email){
        $emailMessage = "Hello,\r Here is you email verification link: https://maxinn.es/verify/$this->verificationCode \rThanks";
        mail($email,"Mercator email code",$emailMessage);
    }

    public static function createEmailValidationRecord($accountId){
        $connection = new DatabaseConnection();
        $currentDate = new DateTime();
        $currentToString = $currentDate->format(DateTimeInterface::ATOM);
        $newVerificationCode = sha1("$currentToString-$accountId");
        $emailValidationNewRecord = array(
            "AccountId"=>$accountId,
            "IsVerified"=>0,
            "VerificationCode"=>$newVerificationCode
        );
        $newVerificationId = $connection->createNewRecord(EmailVerification::TABLE,$emailValidationNewRecord);
        return new EmailVerification($newVerificationId);
    }

    public function isEmailVerified(){
        if($this->isVerified===0){
            return false;
        }else{
            return true;
        }
    }

    public function validateEmailAddress($checkVerificationCode){
        $connection = new DatabaseConnection();
        if($checkVerificationCode==$this->verificationCode){
            $updateRecord = array(
                "IsVerified"=>1,
                "DateVerified"=>date("Y-m-d H:i:s")
            );
            $connection->updateRecord($this::TABLE,$this::TABLE_PRIMARY_KEY,$this->id,$updateRecord);
            return new EmailVerification($this->id);
        }else{
            throw new EmailValidationException("Verification code does not match.");
        }
    }
}