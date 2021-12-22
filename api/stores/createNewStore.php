<?php
// imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Stores.php';
// Header and session
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

// Catch json data
$data = json_decode(file_get_contents('php://input'), true);

$storeName = $data["storeName"];

if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']==true){
    $newStore = Stores::createNewStore($storeName,$_SESSION['accountId']);
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Created new store.",array(
        "storeId"=>$newStore->id
    ));
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"User not logged in.");
}