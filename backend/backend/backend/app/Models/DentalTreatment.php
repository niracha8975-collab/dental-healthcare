<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;



class DentalTreatment extends Model
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

        'service_id',

        'provider_id',

        'treatment_code',

        'tooth_number',

        'surface',

        'diagnosis',

        'procedure_detail',

        'price',

        'status',

        'treatment_date',

        'note'


    ];





    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */


    protected function casts(): array
    {


        return [


            'price'=>'decimal:2',


            'treatment_date'=>'date'


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
     * Dental Record
     */

    public function dentalRecord()
    {


        return $this->belongsTo(

            DentalRecord::class

        );


    }





    /**
     * Dental Service
     */

    public function service()
    {


        return $this->belongsTo(

            DentalService::class,

            'service_id'

        );


    }





    /**
     * Treatment Provider
     */

    public function provider()
    {


        return $this->belongsTo(

            User::class,

            'provider_id'

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





    public function scopeToday($query)
    {


        return $query->whereDate(

            'treatment_date',

            today()

        );


    }





    public function scopeByProvider(

        $query,

        $userId

    )

    {


        return $query->where(

            'provider_id',

            $userId

        );


    }





    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */



    public function complete()
    {


        $this->update([


            'status'=>'completed'


        ]);


    }





    public function cancel()
    {


        $this->update([


            'status'=>'cancelled'


        ]);


    }





    public function isCompleted()
    {


        return $this->status === 'completed';


    }





    public function getFormattedPriceAttribute()
    {


        return number_format(

            $this->price,

            2

        );


    }





    public function getTreatmentLabelAttribute()
    {


        return $this->service

            ? $this->service->name

            : $this->treatment_code;


    }


}