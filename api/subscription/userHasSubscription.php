<?php
// Imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Subscriptions.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';

// Header
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']==true){
    $doesUserHaveSubscription = Subscriptions::doesUserHaveSubscription($_SESSION['accountId']);
    if($doesUserHaveSubscription){
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"User has subscription.",$doesUserHaveSubscription);
    }else{
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"User does NOT have subscription",$doesUserHaveSubscription);
    }
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"User is not logged in");
}