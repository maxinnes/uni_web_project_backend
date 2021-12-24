<?php
// imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Stores.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/StoreProducts.php';
// Header and session
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

$url = $_GET['url'];

if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']==true){
    $storeObj = Stores::getStoreByUrl($url);
    $listOfProductObjs = StoreProducts::getProductsByStoreId($storeObj->id);
    $listOfProducts = array();
    foreach($listOfProductObjs as $productObj){
        $listOfProducts[] = $productObj->returnAsAssocArray();
    }
    $returnObj = array(
        "storeDetails"=>$storeObj->returnAsAssocArray(),
        "productDetails"=>$listOfProducts
    );
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Returned details",$returnObj);
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"User not logged in");
}