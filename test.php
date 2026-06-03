<?php
require_once 'functions.php';

$quantity = 100;
$price = 2.5;
$percent = 10;
$expected = 275;

$result = calculateProductionCost($quantity, $price,$percent);

if ($result == $expected) {
    echo "test passed: cost is " . $result;
} else {
    echo "test failed: Expecete " . $expected . " but got " . $result;
}