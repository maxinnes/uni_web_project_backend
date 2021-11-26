<?php
// TODO Database should have email verification

$age = array("Peter"=>35, "Ben"=>37, "Joe"=>43);

foreach(array_keys($age) as $example){
    echo $example."-";
}

echo "<br>";
echo "<br>";

foreach(array_values($age) as $example){
    echo $example."-";
}