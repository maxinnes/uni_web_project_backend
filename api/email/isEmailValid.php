<?php
// Imports
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/EmailVerification.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/JsonServerResponse.php';

// Header
header('Content-type: application/json');

// Get params
$verificationCode =  $_GET['vc'];

$emailVerificationRecord = EmailVerification::getNewEmailVerificationByCode($verificationCode);


$isVerified = $emailVerificationRecord->isEmailVerified();

if($isVerified){
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Email is valid.");
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"Email is not valid.");
}