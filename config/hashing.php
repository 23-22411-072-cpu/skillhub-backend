<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default hash driver that will be used to hash
    | passwords and other values. By default, the Bcrypt driver is used
    | because it is the most secure and widely used hashing algorithm.
    |
    */

    'default' => env('HASH_DRIVER', 'bcrypt'),

    /*
    |--------------------------------------------------------------------------
    | Hash Drivers
    |--------------------------------------------------------------------------
    |
    | Here you may configure the hash drivers used by the application.
    |
    | The 'bcrypt' driver is a secure, modern hashing algorithm.
    | The 'argon' driver is an alternative modern hashing algorithm.
    |
    */

    'drivers' => [

        'bcrypt' => [
            'driver' => 'bcrypt',
            'rounds' => env('BCRYPT_ROUNDS', 10), // Yeh 10 ya 12 ho sakta hai
        ],

        'argon' => [
            'driver' => 'argon',
            'memory' => 1024,
            'time' => 2,
            'threads' => 2,
        ],
    ],

];