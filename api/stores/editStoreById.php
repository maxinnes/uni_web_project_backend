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

$storeId = $data["storeId"];
$storeName = $data["storeName"];
$url = $data["url"];

if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']==true){
    try{
        $storeObj = new Stores($storeId);
        $storeObj = $storeObj->updateStore($storeName,$url);
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Updated store",$storeObj->returnAsAssocArray());
    }catch(Exception $e){
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,$e->getMessage());
    }
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"User is not logged in.");
}