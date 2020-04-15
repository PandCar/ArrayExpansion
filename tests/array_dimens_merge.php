<?php

/**
 * @author Oleg Isaev (PandCar)
 * @contacts vk.com/id50416641, t.me/pandcar, github.com/pandcar
 */

require '../array_expansion.php';

echo "<pre>\n";

$arr1 = [
    [
        'id' => '12314143-8e78-11e8-9107-50480010f12b',
        'cart' => '12187',
        'sum' => '1800',
        'ssss' => '1'
    ],
    [
        'id' => '1a6f10cf-8e9d-11e8-9109-f8fc001fe6bb',
        'cart' => '23456',
        'sum' => '12075',
        'ssss' => '2'
    ]
];

$arr2 = [
   [
        'id' => '1a6f10cf-8e9d-11e8-9109-f8fc001fe6bb',
        'fdemand' => '2018-07-18 13:48:00',
        'ldemand' => '2018-07-18 13:48:00',
        'sumdemand' => '1',
        'avedemand' => '8800',
        'ssss' => '2'
    ],
    [
        'id' => '12314143-8e78-11e8-9107-50480010f12b',
        'fdemand' => '2018-07-23 16:52:00',
        'ldemand' => '2018-07-23 16:52:00',
        'sumdemand' => '1',
        'avedemand' => '6500',
        'ssss' => '3'
    ]
];

// Слияние массивов по столбцу, только установленные столбцы
$res = array_dimens_merge($arr1, $arr2, 'id', false, [
    'ssss' => 'save-1',
]);

print_r($res);

// Слияние массивов по столбцу, все столбцы + установленные
$res = array_dimens_merge($arr1, $arr2, 'id', true, [
    'ssss' => 'save-1',
]);

print_r($res);

// Слияние массивов по 2-м столбцам, все столбцы + установленные
$res = array_dimens_merge($arr1, $arr2, ['id', 'ssss'], true, [
    'ssss' => 'save-1',
]);

print_r($res);


