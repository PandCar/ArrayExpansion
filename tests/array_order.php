<?php

/**
 * @author Oleg Isaev (PandCar)
 * @contacts vk.com/id50416641, t.me/pandcar, github.com/pandcar
 */

require '../array_expansion.php';

echo "<pre>\n";

$arr = [
    [
        'xxx' => 125,
        'vvv' => 4,
        'items' => [
			'count' => 4,
		],
    ],
    [
        'xxx' => 23,
        'vvv' => 7,
        'items' => [
			'count' => 9,
		],
    ],
    [
        'xxx' => 23,
        'vvv' => 24,
        'items' => [
			'count' => 7,
		],
    ],
    [
        'xxx' => 10,
        'vvv' => 2,
        'items' => [
			'count' => 7,
		],
    ],
];

// Без сохранения ключей
$res = array_order($arr, [
	'items.count' => SORT_ASC, 
	'vvv' => SORT_DESC,
]);

print_r($res);


// С сохранением ключей
$res = array_order($arr, [
	'items.count' => SORT_ASC, 
	'vvv' => SORT_DESC,
], true);

print_r($res);

