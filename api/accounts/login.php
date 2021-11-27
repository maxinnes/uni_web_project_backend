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

$account = Account::getAccountViaEmail($email);

if($account->verifyPassword($password)){
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Logged in","Logged in");
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"Did not log in","did not log in");
}
