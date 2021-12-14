<?php
// Imports
include_once $_SERVER["DOCUMENT_ROOT"].'/models/Account.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';
// Headers
header('Content-type: application/json');
session_start();

$id = $_GET["id"];
try {
    $userAccount = new Account($id);
    $userDetails = array(
        "firstName" => $userAccount->getFirstName(),
        "lastName" => $userAccount->getLastName(),
        "email" => $userAccount->getEmail()
    );
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Got user $id",$userDetails);
}catch (UserDoesNotExistException $e){
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,$e->getMessage());
}