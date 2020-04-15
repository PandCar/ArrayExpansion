<?php

/**
 * @author Oleg Isaev (PandCar)
 * @contacts vk.com/id50416641, t.me/pandcar, github.com/pandcar
 */

require '../array_expansion.php';

echo "<pre>\n";

$arr = Array(
    Array(
        "ID" => "11",
        "FORM_NAME" => "Форма 1",
        "ITEMS" => Array(
            Array(
                "ID" => 1,
                "TSX_0" => "17.03.2020",
                "FORM_ID" => 10
            )
        )
    ),
    Array(
        "ID" => 11,
        "FORM_NAME" => "Форма 1",
        "ITEMS" => Array(
            Array(
                "ID" => 2,
                "TSX_0" => "17.03.2020",
                "FORM_ID" => 11
            )
        )
    ),
    Array(
        "ID" => 12,
        "FORM_NAME" => "Форма 2",
        "ITEMS" => Array(
            Array(
                "ID" => 3,
                "TSX_0" => "17.03.2020",
                "FORM_ID" => 12
            )
        )
    )
);

// Сворачивает массив по столбцам и правилам
$res = array_fold($arr, 'FORM_NAME', [
    'ITEMS' => 'merge-1',
]);

print_r($res);


$res = array_fold($arr, ['ID','FORM_NAME'], [
    'ITEMS' => 'merge-1',
]);

print_r($res);


