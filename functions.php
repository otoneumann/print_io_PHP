<?php
function calculateProductionCost($quantity, $unitPrice, $percent){
    $multiplier = 1 + ($percent/100);
    return ($quantity*$unitPrice) * $multiplier;
}

