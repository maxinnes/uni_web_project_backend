<?php
// Imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Account.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Subscriptions.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';

// Header
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']==true){
    if(Subscriptions::doesUserHaveSubscription($_SESSION['accountId'])){
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Got details",array(
            "doesUserHaveSubscription"=>true
        ));
    }else{
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Got details",array(
            "doesUserHaveSubscription"=>false
        ));
    }
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"User not logged in");
}

