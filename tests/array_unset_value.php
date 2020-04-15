<?php

/**
 * @author Oleg Isaev (PandCar)
 * @contacts vk.com/id50416641, t.me/pandcar, github.com/pandcar
 */

require '../array_expansion.php';

echo "<pre>\n";

$arr = [
	'001', 
	'bar', 
	32,
	'foo' => 'bar',
	'num' => 21,
];


// С сохранением ключей
$tmp = $arr;

array_unset_value($tmp, 'bar');

print_r($tmp);


// Без сохранения ключей
$tmp = $arr;

array_unset_value($tmp, 'bar', false);

print_r($tmp);

