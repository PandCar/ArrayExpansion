<?php

/**
 * @author Oleg Isaev (PandCar)
 * @contacts vk.com/id50416641, t.me/pandcar, github.com/pandcar
 */

require '../array_expansion.php';

echo "<pre>\n";

$arr = [
    'name'    => 'Имя',
    'surname' => 'Фамилия',
    'phone1'  => '+79091111111',
    'phone2'  => '+79092222222',
    'n1'      => 'ssd-123',
    'dss'     => 'ssd-6734',
];

// По значениям
print_r( array_grep($arr, '/^ssd\-\d+/i') );

// По ключам
print_r( array_grep($arr, '/phone\d+/', true) );

