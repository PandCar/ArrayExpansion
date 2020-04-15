<?php

/**
 * @author Oleg Isaev (PandCar)
 * @contacts vk.com/id50416641, t.me/pandcar, github.com/pandcar
 */

require '../array_expansion.php';

echo "<pre>\n";

$arr = [
    [
        'id' => 1,
        'items' => [
			'country' => 'Росиия',
			'count' => 10,
		],
    ],
    [
        'id' => 2,
        'items' => [
			'country' => 'Беларусь',
			'count' => 5,
		],
    ],
    [
        'id' => 3,
        'items' => [
			'country' => 'Украина',
			'count' => 7,
		],
    ],
    [
        'id' => 4,
        'items' => [],
    ],
];


// По правилам 1, один элемент
$res = array_select($arr, [
    ['id', '>', 1], 
    ['items.country', 'regexp', '^Бел'],
]);

print_r($res);


// По правилам 2, список
$res = array_select($arr, [
    ['id', 'in', [1,4]],
	['items', 'not-empty'],
], true);

print_r($res);


// По callback функции, один элемент
$res = array_select($arr, function($item){
	return $item['id'] > 0;
});

print_r($res);

