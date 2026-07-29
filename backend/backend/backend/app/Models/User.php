<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;



class User extends Authenticatable
{


    use HasApiTokens;

    use HasFactory;

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

        'user_type',

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


            'password'=>'hashed',


            'email_verified_at'=>'datetime'


        ];


    }





    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */



    /**
     * Patient Account
     */

    public function patient()
    {


        return $this->hasOne(

            Patient::class

        );


    }





    /**
     * Created Appointment Slots
     */

    public function appointmentSlots()
    {


        return $this->hasMany(

            AppointmentSlot::class,

            'created_by'

        );


    }





    /**
     * Confirmed Appointments
     */

    public function confirmedAppointments()
    {


        return $this->hasMany(

            Appointment::class,

            'confirmed_by'

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





    /**
     * Dental Treatments
     */

    public function treatments()
    {


        return $this->hasMany(

            DentalTreatment::class,

            'provider_id'

        );


    }





    /**
     * Notifications
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





    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */



    public function isAdmin(): bool
    {


        return $this->hasRole(

            'Admin'

        );


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

            'Dental Staff'

        );


    }





    public function isActive(): bool
    {


        return $this->status === 'active';


    }


}