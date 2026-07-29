<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Permission Table Names
    |--------------------------------------------------------------------------
    */

    'table_names' => [

        'roles' => 'roles',

        'permissions' => 'permissions',

        'model_has_permissions' =>
            'model_has_permissions',

        'model_has_roles' =>
            'model_has_roles',

        'role_has_permissions' =>
            'role_has_permissions',

    ],



    /*
    |--------------------------------------------------------------------------
    | Column Names
    |--------------------------------------------------------------------------
    */

    'column_names' => [

        'role_pivot_key' => null,

        'permission_pivot_key' => null,

        'model_morph_key' => 'model_id',

        'team_foreign_key' => 'team_id',

    ],



    /*
    |--------------------------------------------------------------------------
    | Register Permission Check
    |--------------------------------------------------------------------------
    */

    'register_permission_check_method' => true,



    /*
    |--------------------------------------------------------------------------
    | Register Octane Reset Listener
    |--------------------------------------------------------------------------
    */

    'register_octane_reset_listener' => false,



    /*
    |--------------------------------------------------------------------------
    | Display Permission In Exception
    |--------------------------------------------------------------------------
    */

    'display_permission_in_exception' => false,



    /*
    |--------------------------------------------------------------------------
    | Wildcard Permissions
    |--------------------------------------------------------------------------
    */

    'enable_wildcard_permission' => false,



    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    */

    'cache' => [

        'expiration_time' =>
            \DateInterval::createFromDateString(
                '24 hours'
            ),

        'key' => 'spatie.permission.cache',

        'store' => 'default',

    ],

];