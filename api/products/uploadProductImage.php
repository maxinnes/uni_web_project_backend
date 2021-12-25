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

$productId = $_POST["productId"];

//$targetDir = "images/";
//$targetFile = $targetDir.basename($_FILES["productImage"]["name"]);
//$uploadOkay = 1;
//$imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));


if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']==true){
    $productObj = new StoreProducts($productId);
    $check = getimagesize($_FILES["productImage"]["tmp_name"]);
    if($check!==false){
        $temp = explode(".",$_FILES["productImage"]["name"]);
        $newFileName = round(microtime(true)).'.'.end($temp);
        move_uploaded_file($_FILES["productImage"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/images/".$newFileName);
        $productObj = $productObj->addProductImage($newFileName);
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Uploaded file",$productObj->returnAsAssocArray());
    }else{
        echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"Not an image");
    }
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"User not logged in.");
}
