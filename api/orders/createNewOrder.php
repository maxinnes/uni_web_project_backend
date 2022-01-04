<?php
// Imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Orders.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/StoreProducts.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Stores.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/OrderAddress.php';

// Header and session
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

// Catch json data
$data = json_decode(file_get_contents('php://input'), true);

$storeUrl = $data["storeUrl"];
$customerEmail = $data["email"];
$purchasedProducts = $data["purchasedProducts"];
$addressDetails = $data["addressDetails"];

//echo $purchasedProducts;

try{
    $storeObj = Stores::getStoreByUrl($storeUrl);

    $newPurchasedProducts = array();
    $orderTotalPrice = 0;
    foreach($purchasedProducts as $product){
        $newPurchasedProduct = array();
        $productObj = new StoreProducts($product["productId"]);
        $productObjArray = $productObj->returnAsAssocArray();

        $name = $productObjArray["name"];
        $quantity = $product["quantity"];
        $totalPrice = $quantity*$productObjArray["price"];
        $orderTotalPrice = $orderTotalPrice + $totalPrice;

        $newPurchasedProduct["name"] = $name;
        $newPurchasedProduct["quantity"] = $quantity;
        $newPurchasedProduct["total"] = $totalPrice;
        $newPurchasedProducts[] = $newPurchasedProduct;
    }
//    $newPurchasedProducts = json_encode($newPurchasedProducts);
    // Create new order
    $newOrderObj = Orders::createNewOrder($orderTotalPrice,$newPurchasedProducts,$storeObj->id,$customerEmail);

    $addressLineOne = $addressDetails["addressLineOne"];
    $addressLineTwo = $addressDetails["addressLineTwo"];
    $addressLineThree = $addressDetails["addressLineThree"];
    $city = $addressDetails["city"];
    $county = $addressDetails["county"];
    $postcode = $addressDetails["postcode"];

    $customerAddresses = OrderAddress::createNewAddress($newOrderObj->id,$addressLineOne,$addressLineTwo,$addressLineThree,$city,$county,$postcode);
    // Create customer address
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Order created",$newOrderObj->returnAsAssocArray());
}catch(Exception $e){
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,$e->getMessage());
}
