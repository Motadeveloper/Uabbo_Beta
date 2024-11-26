<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Hashing Driver
    |--------------------------------------------------------------------------
    |
    | Aqui você pode especificar o driver de hashing que será usado pelo 
    | framework. A configuração "bcrypt" é a padrão para a maioria das aplicações.
    | Você pode mudar isso para "argon" ou "argon2id" se desejar.
    |
    */

    'default' => 'bcrypt',

    /*
    |--------------------------------------------------------------------------
    | Bcrypt Options
    |--------------------------------------------------------------------------
    |
    | Aqui você pode especificar as opções para o algoritmo Bcrypt.
    |
    */

    'bcrypt' => [
        'rounds' => 10, // Número de iterações. 10 é o padrão.
    ],

    /*
    |--------------------------------------------------------------------------
    | Argon Options
    |--------------------------------------------------------------------------
    |
    | Aqui você pode especificar as opções para o algoritmo Argon.
    |
    */

    'argon' => [
        'memory' => 1024,
        'threads' => 2,
        'time' => 2,
    ],
];
