<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;


/*
|--------------------------------------------------------------------------
| Dental Healthcare Management System
| Console Routes
|--------------------------------------------------------------------------
|
| Console routes define Artisan commands that use closures.
|
| Business commands should be implemented as dedicated Command Classes.
|
*/


/*
|--------------------------------------------------------------------------
| Default Laravel Inspire Command
|--------------------------------------------------------------------------
|
| Example command for testing Artisan functionality.
|
*/

Artisan::command('inspire', function () {

    $this->comment(
        Inspiring::quote()
    );

})->purpose(
    'Display an inspiring quote'
);



/*
|--------------------------------------------------------------------------
| System Maintenance Commands
|--------------------------------------------------------------------------
|
| Future commands will be registered here:
|
| dental:appointment-reminder
| dental:sync-mypcu
| dental:backup
| dental:cleanup
|
|
| Example:
|
| Artisan::command('dental:health-check', function () {
|
|     $this->info('System healthy');
|
| })->purpose('Check Dental Healthcare System status');
|
*/