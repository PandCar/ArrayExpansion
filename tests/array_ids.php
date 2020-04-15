<?php

/**
 * @author Oleg Isaev (PandCar)
 * @contacts vk.com/id50416641, t.me/pandcar, github.com/pandcar
 */

require '../array_expansion.php';

echo "<pre>\n";

$array = ['sfdf', 0, null, 3, 35, '007,', 0, 3];

print_r( array_ids($array) );

print_r( array_ids($array, false) );

