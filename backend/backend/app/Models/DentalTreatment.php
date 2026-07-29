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


        'dental_record_id',

        'service_id',

        'dentist_id',

        'tooth_number',

        'treatment_date',

        'diagnosis',

        'procedure_detail',

        'material_used',

        'cost',

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


            'treatment_date'=>'date',


            'cost'=>'decimal:2',


        ];

    }



    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */


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
     * Dentist
     */
    public function dentist()
    {

        return $this->belongsTo(

            User::class,

            'dentist_id'

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



    public function scopeByTooth(

        $query,

        $toothNumber

    )
    {

        return $query->where(

            'tooth_number',

            $toothNumber

        );

    }



    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */


    /**
     * Complete Treatment
     */
    public function complete(): void
    {

        $this->update([

            'status'=>'completed'

        ]);

    }



    /**
     * Cancel Treatment
     */
    public function cancel(): void
    {

        $this->update([

            'status'=>'cancelled'

        ]);

    }



    /**
     * Check Completed
     */
    public function isCompleted(): bool
    {

        return $this->status === 'completed';

    }



    /**
     * Formatted Cost
     */
    public function getFormattedCostAttribute()
    {

        return number_format(

            $this->cost,

            2

        )

        . ' บาท';

    }



}