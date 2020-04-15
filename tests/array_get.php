<?php

/**
 * @author Oleg Isaev (PandCar)
 * @contacts vk.com/id50416641, t.me/pandcar, github.com/pandcar
 */

require '../array_expansion.php';

echo "<pre>\n";

$arr = [
	'foo' => 'bar',
];

var_dump( array_get($arr, 'foo', 1) );

var_dump( array_get($arr, 'two', 1) );

