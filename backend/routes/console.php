<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Artisan;


/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| Dental Healthcare Scheduled Tasks
|
*/


/*
|--------------------------------------------------------------------------
| System Health Check
|--------------------------------------------------------------------------
*/

Artisan::command(
    'system:health',
    function () {

        $this->info(
            'Dental Healthcare System is running.'
        );

    }
);



/*
|--------------------------------------------------------------------------
| Appointment Reminder
|--------------------------------------------------------------------------
|
| Send notification before appointment.
|
*/

Schedule::command(
    'appointment:reminder'
)
->dailyAt('08:00');



/*
|--------------------------------------------------------------------------
| MyPCU Synchronization
|--------------------------------------------------------------------------
|
| Sync healthcare data.
|
*/

Schedule::command(
    'mypcu:sync'
)
->dailyAt('02:00');



/*
|--------------------------------------------------------------------------
| Generate Dental Reports
|--------------------------------------------------------------------------
|
| Monthly statistics.
|
*/

Schedule::command(
    'dental:report'
)
->monthlyOn(
    1,
    '03:00'
);



/*
|--------------------------------------------------------------------------
| Database Backup
|--------------------------------------------------------------------------
*/

Schedule::command(
    'database:backup'
)
->weekly();



/*
|--------------------------------------------------------------------------
| Clean Old Logs
|--------------------------------------------------------------------------
*/

Schedule::command(
    'logs:cleanup'
)
->weekly();