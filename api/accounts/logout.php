<?php
// Imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';

// Header
header('Content-type: application/json');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

// Check if logged in
if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']==true){
    session_unset();
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_SUCCESSFUL,"Successfully logged out.");
}else{
    echo JsonServerResponse::createJsonResponse(JsonServerResponse::MESSAGE_FAIL,"You are not logged in.");
}

//if(session_status()==PHP_SESSION_NONE){
//
//}