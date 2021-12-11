<?php
// Imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Account.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';

// Header
header('Content-type: application/json');

// Catch json data
$data = json_decode(file_get_contents('php://input'), true);

// Get json contents
$firstName = $data["firstName"];
$lastName = $data["lastName"];
$email = $data["email"];
$password = $data["password"]; // TODO Sanitise input

// Create account
try {
    $newUserAccount = Account::createNewAccount($firstName, $lastName, $email, $password);
    $returnId = array("accountId"=>$newUserAccount->id);
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Account successfully created.",$returnId);
} catch(UserAlreadyExistsException $e){
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,$e->getMessage());
}