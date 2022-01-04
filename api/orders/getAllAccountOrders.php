<?php
// Imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Stores.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Orders.php';

// Header and session
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']==true){
    try{
        $usersStores = Stores::getStoresByAccountId($_SESSION['accountId']);

        $listOfAllOrders = array();
        foreach($usersStores as $store){
            $storeDetails = $store->returnAsAssocArray();
            $listOfOrdersProducts = Orders::getAllOrdersByStoreId($store->id);
            foreach($listOfOrdersProducts as $ordersProduct){
                $tempArray = $ordersProduct->returnAsAssocArray();
                $tempArray["purchasedProducts"] = json_decode($tempArray["purchasedProducts"]);
                $tempArray["storeName"] = $storeDetails["storeName"];
//                $listOfAllOrders[] = $ordersProduct->returnAsAssocArray();
                $listOfAllOrders[] = $tempArray;
            }
        }

        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"",$listOfAllOrders);

    }catch(Exception $e){
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,$e->getMessage());
    }
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"User not logged in");
}