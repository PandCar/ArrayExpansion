<?php

/**
 * @author Oleg Isaev (PandCar)
 * @contacts vk.com/id50416641, t.me/pandcar, github.com/pandcar
 */

require '../array_expansion.php';

echo "<pre>\n";

$arr1 = [
	'id' => '12314143-8e78-11e8-9107-50480010f12b',
	'cart' => '12187',
	'sum' => '1800',
	'ssss' => '23',
	'arr' => [1,2,3],
];
	
$arr2 = [
	'id' => '1a6f10cf-8e9d-11e8-9109-f8fc001fe6bb',
	'fdemand' => '2018-07-18 13:48:00',
	'ldemand' => '2018-07-18 13:48:00',
	'sumdemand' => '1',
	'avedemand' => '8800',
	'ssss' => '11',
	'arr' => [6,3,5],
];

// Все столбцы
$res = array_merge_by_rule($arr1, $arr2, true, [
    'arr' => 'merge',
]);

print_r($res);

// Только по выбранные
$res = array_merge_by_rule($arr1, $arr2, false, [
    'arr' => 'merge',
]);

print_r($res);
