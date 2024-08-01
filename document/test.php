<?php
$arr = [["1", "2", "3"], ["2", "2", "3"]];
$encodedString = json_encode($arr);
echo $encodedString;
$list = json_decode($encodedString, true);
print_r($list);
?>