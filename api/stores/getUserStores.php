<?php
// imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Stores.php';
// Header and session
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']==true){
    $listOfStores = Stores::getStoresByAccountId($_SESSION['accountId']);
    $arrayOfStores = [];
    foreach($listOfStores as $store){
        $arrayOfStores[] = $store->returnAsAssocArray();
    }
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Returned stores",$arrayOfStores);
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"User not logged in");
}