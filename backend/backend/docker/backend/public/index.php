<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check Maintenance Mode
|--------------------------------------------------------------------------
|
| Laravel will load the cached maintenance file if the application
| is currently placed into maintenance mode.
|
*/

if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Composer Autoloader
|--------------------------------------------------------------------------
|
| Composer provides the autoload functionality for Laravel classes.
|
*/

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Bootstrap Laravel And Handle Request
|--------------------------------------------------------------------------
|
| Create the application instance and handle the incoming HTTP request.
|
*/

$app = require_once __DIR__ . '/../bootstrap/app.php';


$app->handleRequest(Request::capture());