<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | Algorithm used to securely hash passwords.
    |
    */

    'driver' => env(
        'HASH_DRIVER',
        'bcrypt'
    ),



    /*
    |--------------------------------------------------------------------------
    | Bcrypt Options
    |--------------------------------------------------------------------------
    |
    | Cost factor controls password hashing strength.
    |
    */

    'bcrypt' => [

        'rounds' => env(
            'BCRYPT_ROUNDS',
            12
        ),

    ],



    /*
    |--------------------------------------------------------------------------
    | Argon Options
    |--------------------------------------------------------------------------
    |
    | Available for stronger password hashing.
    |
    */

    'argon' => [

        'memory' => 65536,

        'threads' => 1,

        'time' => 4,

    ],



    /*
    |--------------------------------------------------------------------------
    | Rehash Passwords
    |--------------------------------------------------------------------------
    |
    | Automatically upgrade hashes when configuration changes.
    |
    */

    'rehash_on_login' => true,

];