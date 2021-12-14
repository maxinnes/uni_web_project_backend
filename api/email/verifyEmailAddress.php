<?php
// Imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/EmailVerification.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';

// Header
header('Content-type: application/json');

// Get params
$verificationCode =  $_GET['vc'];

$verificationRecord = EmailVerification::getNewEmailVerificationByCode($verificationCode);
if(!$verificationRecord->isEmailVerified()) {
    try {
        $verificationRecord = $verificationRecord->validateEmailAddress($verificationCode);
        $isEmailValid = $verificationRecord->isEmailVerified();
        if ($isEmailValid) {
            echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL, "Email address verified");
        } else {
            echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL, "Email was not validated.");
        }
    } catch (EmailValidationException $e) {
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL, $e->getMessage());
    }
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"Email already verified.");
}