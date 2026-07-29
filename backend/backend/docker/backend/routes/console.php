<?php

use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| Define Artisan commands and scheduled tasks here.
| This file is loaded automatically by Laravel.
|
*/


/*
|--------------------------------------------------------------------------
| Application Health Monitoring
|--------------------------------------------------------------------------
|
| Example scheduled health monitoring.
| Real monitoring jobs will be added in future modules.
|
*/

Schedule::call(function (): void {

    logger()->info(
        'Dental Healthcare System heartbeat check completed.'
    );

})
    ->dailyAt('00:00');



/*
|--------------------------------------------------------------------------
| Future Healthcare Scheduler
|--------------------------------------------------------------------------
|
| Planned tasks:
|
| - Appointment reminder notification
| - MyPCU data synchronization
| - Dental report generation
| - Database backup
|
*/