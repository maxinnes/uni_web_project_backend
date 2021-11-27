<?php
// TODO Database should have email verification

$salt = openssl_random_pseudo_bytes(16);
$hash = hash_pbkdf2("sha256", "Simple_123", $salt, 1000, 20);

print($salt);

print("<br>");

print(base64_encode($salt));

print("<br>");

print($hash);
