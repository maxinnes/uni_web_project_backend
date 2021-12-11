<?php
// Imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Account.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/models/JsonServerResponse.php';

$verificationCode =  $_GET['vc'];

echo Account::isEmailVerified($verificationCode);




