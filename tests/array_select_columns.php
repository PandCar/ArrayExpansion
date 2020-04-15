<?php

/**
 * @author Oleg Isaev (PandCar)
 * @contacts vk.com/id50416641, t.me/pandcar, github.com/pandcar
 */

require '../array_expansion.php';

echo "<pre>\n";

$arr = [
    [
        'some' => 'somevalue',
        'some2' => 'some2value',
        'some1' => 'some1value',
        'id' => 123
    ],
    [
        'some' => '12',
        'some2' => '44',
        'some1' => '757',
        'id' => 5443
    ],
];

$columns = [
    'some2',
    'id',
];

// По одномерному массиву
print_r( array_select_columns($arr[0], $columns) );

// По списку
print_r( array_select_columns($arr, $columns, true) );

