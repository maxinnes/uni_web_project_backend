<?php
// Imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Account.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';

// Header
header('Content-type: application/json');

// Catch json data
$data = json_decode(file_get_contents('php://input'), true);

$email = $data["email"];
$password = $data["password"];

try {
    $account = Account::getAccountViaEmail($email);
    if($account->verifyPassword($password)){
        if(session_status()==PHP_SESSION_ACTIVE){
            $sessionDetails = array(
                "sessionStatus"=> session_status(),
                "sessionId"=> session_id(),
                "sessionAccountId"=>$_SESSION['sessionAccountId']
            );
            echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"Session already exists",$sessionDetails);
        }else{
            session_start();
            $_SESSION['accountObject'] = $account;
            $_SESSION['isLoggedIn'] = true; // TODO Record session IP as well
            $sessionDetails = array(
                "sessionStatus"=> session_status(),
                "sessionId"=> session_id(),
                "sessionAccountId"=>$account->id
            );
        }
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Logged in",$sessionDetails);
    }else{
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"Did not log in","did not log in");
    }
}catch (UserDoesNotExistException $e){
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,$e->getMessage());
}