<?php

include_once $_SERVER["DOCUMENT_ROOT"].'/models/Account.php';

header('Content-type: application/json');

$id = $_GET["id"];

$userAccount = new Account($id);

$userDetails = array(
    "firstName" => $userAccount->getFirstName(),
    "lastName" => $userAccount->getLastName(),
    "email" => $userAccount->getEmail()
);

echo json_encode($userDetails);
