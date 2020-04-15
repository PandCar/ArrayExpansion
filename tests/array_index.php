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
];

// Стандартно
print_r( array_index($arr, 'xxx') );

// Групировать в списки
print_r( array_index($arr, 'xxx', true) );

// Уникальность ключей по двум стобцам + групировка
print_r( array_index($arr, ['xxx', 'vvv'], true) );

// Уникальность ключей по двум стобцам + групировка + убран разделитель
print_r( array_index($arr, ['xxx', 'vvv'], true, '') );

