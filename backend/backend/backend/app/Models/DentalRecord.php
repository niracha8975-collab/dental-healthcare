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

        'treatment_plan',

        'dmft_score',

        'decayed_teeth',

        'missing_teeth',

        'filled_teeth',

        'ohsp_code',

        'clinical_note',

        'mypcu_record_id',

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


            'visit_date'=>'date',


            'last_sync_at'=>'datetime',


            'dmft_score'=>'decimal:2'


        ];


    }





    /*
    |--------------------------------------------------------------------------

    | Relationships
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
     * Odontograms
     */

    public function odontograms()
    {


        return $this->hasMany(

            Odontogram::class

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





    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */



    public function scopeChild($query)
    {


        return $query->where(

            'ohsp_code',

            '18.5'

        );


    }





    public function scopeLatestVisit($query)
    {


        return $query->orderBy(

            'visit_date',

            'desc'

        );


    }





    /*
    |--------------------------------------------------------------------------
    | Dental Calculation
    |--------------------------------------------------------------------------
    */



    public function calculateDMFT()
    {


        $this->dmft_score =

            $this->decayed_teeth +

            $this->missing_teeth +

            $this->filled_teeth;



        $this->save();



        return $this->dmft_score;


    }





    public function hasCaries()
    {


        return $this->decayed_teeth > 0;


    }





    public function needTreatment()
    {


        return !empty(

            $this->treatment_plan

        );


    }





    /*
    |--------------------------------------------------------------------------
    | My PCU Integration
    |--------------------------------------------------------------------------
    */



    public function needSyncMyPCU()
    {


        return empty(

            $this->mypcu_record_id

        );


    }


}