<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;



class Odontogram extends Model
{


    use HasFactory;



    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */


    protected $fillable = [


        'patient_id',

        'dental_record_id',

        'tooth_number',

        'dentition',

        'surface',

        'condition',

        'diagnosis',

        'treatment',

        'note'


    ];





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
     * Dental Record
     */

    public function dentalRecord()
    {


        return $this->belongsTo(

            DentalRecord::class

        );


    }





    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */



    public function scopePermanent($query)
    {


        return $query->where(

            'dentition',

            'permanent'

        );


    }





    public function scopePrimary($query)
    {


        return $query->where(

            'dentition',

            'primary'

        );


    }





    public function scopeCaries($query)
    {


        return $query->where(

            'condition',

            'caries'

        );


    }





    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */



    public function isCaries()
    {


        return $this->condition === 'caries';


    }





    public function isMissing()
    {


        return $this->condition === 'missing';


    }





    public function needTreatment()
    {


        return !empty(

            $this->treatment

        );


    }





    public function getToothLabelAttribute()
    {


        return match(

            $this->dentition

        ){


            'permanent' => 'ฟันแท้ '.$this->tooth_number,


            'primary' => 'ฟันน้ำนม '.$this->tooth_number,


            default => $this->tooth_number


        };


    }


}