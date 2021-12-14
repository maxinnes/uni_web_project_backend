<?php
// imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';

// Header and session
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']==true){
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"User is logged in");
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"User is NOT logged in");
}