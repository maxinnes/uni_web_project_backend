<?php
// imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/StoreProducts.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Stores.php';

// Header and session
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

// Catch json data
$data = json_decode(file_get_contents('php://input'), true);

$storeId = $data["storeId"];
$productId = $data["productId"];
$name = $data["name"];
$description = $data["description"];
$price = $data["price"];

if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']==true){
    try{
        $storeObj = new Stores($storeId);
        $storeArray = $storeObj->returnAsAssocArray();
        if($storeArray["accountId"]===$_SESSION['accountId']){
            $productObj = new StoreProducts($productId);
            $newProductObj = $productObj->updateProduct($name,$description,$price);
            echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Successfully updated new product",$newProductObj->returnAsAssocArray());
        }else{
            echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"User tried to access store which does not belong to them.");
        }
    }catch(Exception $e){
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,$e->getMessage());
    }
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"User is not logged in.");
}