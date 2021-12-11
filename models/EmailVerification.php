<?php
// Imports
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/DatabaseConnection.php';

class EmailVerification{

    private const TABLE = "EmailValidation";

    // TODO sendVerificationEmail()
    // TODO

    public static function sendValidationEmail($email){
        $emailMessage = "Hello,\r\ Here is you email verification link: http://localhost/#/verify/$newVerificationCode";
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
        $connection->createNewRecord(EmailVerification::TABLE,$emailValidationNewRecord);
        return $newVerificationCode;
    }

    public static function isEmailVerified($verificationCode){
        $connection = new DatabaseConnection();
        $validationRecord = $connection->getOneRecordByAttribute(EmailVerification::TABLE,"VerificationCode",$verificationCode);
        if($validationRecord["IsVerified"]==0){
            return false;
        }else{
            return true;
        }
    }
}
