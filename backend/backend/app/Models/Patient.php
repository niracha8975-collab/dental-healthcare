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

        'birth_date',

        'gender',

        'blood_type',

        'address',

        'phone',

        'emergency_contact',

        'medical_condition',

        'drug_allergy',

        'status'


    ];



    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {

        return [


            'birth_date' => 'date',


        ];

    }



    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */


    /**
     * Link User Account
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
     * Dental Treatments
     */
    public function dentalTreatments()
    {

        return $this->hasManyThrough(

            DentalTreatment::class,

            DentalRecord::class

        );

    }



    /**
     * Odontograms
     */
    public function odontograms()
    {

        return $this->hasMany(
            Odontogram::class
        );

    }



    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */


    /**
     * Full Name
     */
    public function getFullNameAttribute()
    {

        return trim(

            $this->prefix.' '.

            $this->first_name.' '.

            $this->last_name

        );

    }



    /**
     * Age Calculation
     */
    public function getAgeAttribute()
    {

        if(!$this->birth_date)
        {

            return null;

        }


        return $this->birth_date
            ->age;

    }



    /**
     * Check Active Patient
     */
    public function isActive(): bool
    {

        return $this->status === 'active';

    }



}