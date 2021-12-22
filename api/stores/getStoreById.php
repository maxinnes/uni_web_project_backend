<?php
// imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Stores.php';
// Header and session
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

$storeId = $_GET['id'];

if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']==true){
    $storeObj = new Stores($storeId);
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Returned details",$storeObj->returnAsAssocArray());
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"User not logged in");
}