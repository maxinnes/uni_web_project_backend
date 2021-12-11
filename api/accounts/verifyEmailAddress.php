<?php
// Imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/EmailVerification.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';

// Header
header('Content-type: application/json');

// Get params
$verificationCode =  $_GET['vc'];

$isVerified = EmailVerification::isEmailVerified($verificationCode);

if($isVerified){
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Email is verified",$isVerified);
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Email is not verified",$isVerified);
}