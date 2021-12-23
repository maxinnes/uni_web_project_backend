<?php
// imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/StoreProducts.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Stores.php';

// Header and session
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

$id = $_GET['id'];

if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']==true){
    $productObj = new StoreProducts($id);
    $productDetails = $productObj->returnAsAssocArray();
    $storeObj = new Stores($productDetails['storeId']);
    $storeDetails = $storeObj->returnAsAssocArray();
    if($_SESSION['accountId']===$storeDetails['accountId']){
        try {
            $productObj->deleteProduct();
            echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Deleted product.");
        } catch(Exception $e){
            echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,$e->getMessage());
        }
    }else{
        JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"User does not own store");
    }
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"User not logged in");
}