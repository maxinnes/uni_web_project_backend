<?php
// Imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Subscriptions.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';

// Header
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']==true) {
    // Catch json data
    $data = json_decode(file_get_contents('php://input'), true);

    // Get json contents
    $subscriptionChoice = $data["subscriptionChoice"];
    $price = null;

    switch ($subscriptionChoice) {
        case "1":
            $price = 0;
            break;
        case "2":
            $price = 5;
            break;
        case "3":
            $price = 25;
            break;
    }

    if($price===null){
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"Choice not valid",$price);
    }else{
        try {
            $newSubscription = Subscriptions::createNewSubscription($_SESSION['accountId'], $price, $subscriptionChoice);
            echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL, "Subscription Created");
        } catch(UserHasSubscriptionException $e){
            echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,$e->getMessage());
        }
    }
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"User not logged in");
}
