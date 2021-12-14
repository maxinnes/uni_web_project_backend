<?php
// Imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Account.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/EmailVerification.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';

// Header
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

// Catch json data
$data = json_decode(file_get_contents('php://input'), true);

$email = $data["email"];
$password = $data["password"];

try {
    // Get account
    $account = Account::getAccountViaEmail($email);
    // Is email verified
    $emailVerificationRecord = EmailVerification::getNewEmailVerificationByAccountId($account->id);
    if($account->verifyPassword($password)){
        if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']==true){
            $sessionDetails = array(
                "sessionId"=> session_id(),
                "sessionAccountId"=>$_SESSION['accountObject']->id
            );
            echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"Session already exists",$sessionDetails);
        }else {
            if($emailVerificationRecord->isEmailVerified()) {
                $_SESSION['accountObject'] = $account;
                $_SESSION['isLoggedIn'] = true; // TODO Record session IP as well
                echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL, "Logged in.");
            }else{
                echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"Email not verified.");
            }
        }
    }else{
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"Did not log in","did not log in");
    }
}catch (UserDoesNotExistException $e){
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,$e->getMessage());
}