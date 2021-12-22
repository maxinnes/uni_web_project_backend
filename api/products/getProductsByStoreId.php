<?php
// imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/StoreProducts.php';

// Header and session
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

$id = $_GET['id'];

try{
    $returnArray = array();
    $listOfProductObjs = StoreProducts::getProductsByStoreId($id);
    foreach($listOfProductObjs as $productObj){
        $returnArray[] = $productObj->returnAsAssocArray();
    }
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Return products",$returnArray);
}catch(Exception $e){
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,$e->getMessage());
}