<?php
// Imports
include_once $_SERVER["DOCUMENT_ROOT"].'/models/Account.php';
include_once $_SERVER["DOCUMENT_ROOT"].'/models/AccountAddresses.php';
include_once $_SERVER["DOCUMENT_ROOT"].'/models/Subscriptions.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';
// Headers
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']==true){
    $accountObj = new Account($_SESSION['accountId']);
    try {
        $subscriptionObj = Subscriptions::getSubscriptionByAccountId($_SESSION['accountId']);
        $addressObj = AccountAddresses::getAccountAddressByAccountId($_SESSION['accountId']);
        if($addressObj==null){
            echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL, "Got results", array(
                "accountDetails" => $accountObj->returnAsAssocArray(),
                "addressDetails" => null,
                "subscriptionDetails" => $subscriptionObj->returnAsAssocArray()
            ));
        }else {
            echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL, "Got results", array(
                "accountDetails" => $accountObj->returnAsAssocArray(),
                "addressDetails" => $addressObj->returnAsAssocArray(),
                "subscriptionDetails" => $subscriptionObj->returnAsAssocArray()
            ));
        }
    } catch(Exception $e){
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,$e->getMessage());
    }
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"User not logged in.");
}