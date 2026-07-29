<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;



class DentalRecord extends Model
{


    use HasFactory;



    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [


        'patient_id',

        'appointment_id',

        'dentist_id',

        'visit_date',

        'chief_complaint',

        'diagnosis',

        'oral_condition',

        'treatment_summary',

        'follow_up_date',

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


            'visit_date'=>'date',


            'follow_up_date'=>'date',


        ];

    }



    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */


    /**
     * Patient
     */
    public function patient()
    {

        return $this->belongsTo(

            Patient::class

        );

    }



    /**
     * Appointment
     */
    public function appointment()
    {

        return $this->belongsTo(

            Appointment::class

        );

    }



    /**
     * Dentist
     */
    public function dentist()
    {

        return $this->belongsTo(

            User::class,

            'dentist_id'

        );

    }



    /**
     * Treatments
     */
    public function treatments()
    {

        return $this->hasMany(

            DentalTreatment::class

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
    | Query Scope
    |--------------------------------------------------------------------------
    */


    public function scopeCompleted($query)
    {

        return $query->where(

            'status',

            'completed'

        );

    }



    public function scopePatient(

        $query,

        $patientId

    )
    {

        return $query->where(

            'patient_id',

            $patientId

        );

    }



    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */


    /**
     * Complete Record
     */
    public function complete(): void
    {

        $this->update([

            'status'=>'completed'

        ]);

    }



    /**
     * Check Follow Up
     */
    public function hasFollowUp(): bool
    {

        return !empty(

            $this->follow_up_date

        );

    }



    /**
     * Total Treatment Count
     */
    public function getTreatmentCountAttribute()
    {

        return $this->treatments()
            ->count();

    }



}