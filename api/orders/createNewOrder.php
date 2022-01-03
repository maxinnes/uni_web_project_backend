<?php
// Imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Orders.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/StoreProducts.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Stores.php';

// Header and session
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

// Catch json data
$data = json_decode(file_get_contents('php://input'), true);

$purchasedProducts = $data["purchaseProducts"];
$storeUrl = $data["storeUrl"];

$storeObj = Stores::getStoreByUrl($storeUrl);

echo json_encode($storeObj->returnAsAssocArray());
