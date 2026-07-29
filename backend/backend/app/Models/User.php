<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;



class User extends Authenticatable
{


    use HasApiTokens;

    use Notifiable;

    use HasRoles;



    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [


        'name',

        'email',

        'phone',

        'password',

        'status'


    ];



    /*
    |--------------------------------------------------------------------------
    | Hidden Fields
    |--------------------------------------------------------------------------
    */

    protected $hidden = [


        'password',

        'remember_token'


    ];



    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {

        return [


            'password' => 'hashed',


        ];

    }



    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */


    /**
     * User Devices
     */
    public function deviceTokens()
    {

        return $this->hasMany(
            DeviceToken::class
        );

    }



    /**
     * User Notifications
     */
    public function notifications()
    {

        return $this->hasMany(
            Notification::class
        );

    }



    /**
     * Audit Logs
     */
    public function auditLogs()
    {

        return $this->hasMany(
            AuditLog::class
        );

    }



    /**
     * Created Appointments
     */
    public function createdAppointments()
    {

        return $this->hasMany(
            Appointment::class,
            'created_by'
        );

    }



    /**
     * Dental Records
     */
    public function dentalRecords()
    {

        return $this->hasMany(
            DentalRecord::class,
            'dentist_id'
        );

    }



    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */


    public function isAdmin(): bool
    {

        return $this->hasAnyRole([

            'Super Admin',

            'Admin'

        ]);

    }



    public function isDentist(): bool
    {

        return $this->hasRole(
            'Dentist'
        );

    }



    public function isStaff(): bool
    {

        return $this->hasRole(
            'Staff'
        );

    }



    public function isCitizen(): bool
    {

        return $this->hasRole(
            'Citizen'
        );

    }



}