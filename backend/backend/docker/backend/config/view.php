<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Locations where view files are stored.
    |
    */

    'paths' => [

        resource_path(
            'views'
        ),

    ],



    /*
    |--------------------------------------------------------------------------
    | Compiled View Storage
    |--------------------------------------------------------------------------
    |
    | Location for compiled Blade templates.
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(
            storage_path(
                'framework/views'
            )
        )
    ),



];