<?php
// TODO Database should have email verification

//$age = array("Peter"=>35, "Ben"=>37, "Joe"=>43);
//
//foreach(array_keys($age) as $example){
//    echo $example."-";
//}
//
//echo "<br>";
//echo "<br>";
//
//foreach(array_values($age) as $example){
//    echo $example."-";
//}

$salt = openssl_random_pseudo_bytes(16);
$hash = hash_pbkdf2("sha256", "Simple_123", $salt, 1000, 20);

print($salt);

print($hash);
