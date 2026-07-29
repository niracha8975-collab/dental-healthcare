<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;



class Patient extends Model
{


    use HasFactory;



    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */


    protected $fillable = [


        'user_id',

        'hn',

        'citizen_id',

        'prefix',

        'first_name',

        'last_name',

        'gender',

        'birth_date',

        'phone',

        'email',

        'address',

        'blood_type',

        'allergy',

        'medical_history',

        'emergency_contact',

        'mypcu_patient_id',

        'last_sync_at'


    ];





    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */


    protected function casts(): array
    {


        return [


            'birth_date'=>'date',


            'last_sync_at'=>'datetime'


        ];


    }





    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */



    /**
     * Account Owner
     */

    public function user()
    {


        return $this->belongsTo(

            User::class

        );


    }





    /**
     * Appointments
     */

    public function appointments()
    {


        return $this->hasMany(

            Appointment::class

        );


    }





    /**
     * Dental Records
     */

    public function dentalRecords()
    {


        return $this->hasMany(

            DentalRecord::class

        );


    }





    /**
     * Odontogram
     */

    public function odontograms()
    {


        return $this->hasMany(

            Odontogram::class

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





    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */



    public function getFullNameAttribute()
    {


        return trim(

            $this->prefix.' '.

            $this->first_name.' '.

            $this->last_name

        );


    }





    public function getAgeAttribute()
    {


        if(!$this->birth_date)

        {

            return null;

        }



        return $this->birth_date

            ->age;


    }





    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */



    public function isChild()
    {


        return $this->age < 15;


    }





    public function hasDentalHistory()
    {


        return $this->dentalRecords()

            ->exists();


    }





    public function needSyncMyPCU()
    {


        return empty(

            $this->mypcu_patient_id

        );


    }


}