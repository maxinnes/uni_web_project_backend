<?php
// Imports
include_once $_SERVER['DOCUMENT_ROOT']."/models/JsonServerResponse.php";
include_once $_SERVER['DOCUMENT_ROOT']."/models/Orders.php";
include_once $_SERVER['DOCUMENT_ROOT']."/models/OrderAddress.php";
include_once $_SERVER['DOCUMENT_ROOT']."/models/Stores.php";

// Header and session
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

$id = $_GET["id"];

if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']==true){
    try{
        $returnArray = array();
        $orderObj = new Orders($id);
        $orderAddressObj = OrderAddress::getOrderAddressByOrderId($id);

        $tempArray = $orderObj->returnAsAssocArray();
        $tempArray["purchasedProducts"] = json_decode($tempArray["purchasedProducts"]);
        $tempStoreObj = new Stores($tempArray["storeId"]);
        $tempStoreObjArr = $tempStoreObj->returnAsAssocArray();
        $tempArray["storeName"] = $tempStoreObjArr["storeName"];

        $returnArray["orderDetails"] = $tempArray;
        $returnArray["orderAddressDetails"] = $orderAddressObj->returnAsAssocArray();
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Done",$returnArray);
    }catch(Exception $e){
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,$e->getMessage());
    }
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"User is NOT logged in");
}
