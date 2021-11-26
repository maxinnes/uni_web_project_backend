<?php
// Imports
include_once $_SERVER['DOCUMENT_ROOT'].'/models/Account.php';

// Header
header('Content-type: application/json');

// Catch json data
$data = json_decode(file_get_contents('php://input'), true);

// Get json contents
$firstName = $data["firstName"];
$lastName = $data["lastName"];
$email = $data["email"];
$password = $data["password"];

// Create account
Account::createNewAccount($firstName,$lastName,$email,$password);
