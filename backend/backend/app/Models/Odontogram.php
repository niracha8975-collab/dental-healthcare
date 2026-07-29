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

        'dentition_type',

        'tooth_status',

        'surface_data',

        'note',

        'examined_by',

        'examined_date'


    ];



    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {

        return [


            'surface_data'=>'array',


            'examined_date'=>'date',


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
     * Dental Record
     */
    public function dentalRecord()
    {

        return $this->belongsTo(

            DentalRecord::class

        );

    }



    /**
     * Examiner
     */
    public function examiner()
    {

        return $this->belongsTo(

            User::class,

            'examined_by'

        );

    }



    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */


    public function scopeCaries($query)
    {

        return $query->where(

            'tooth_status',

            'caries'

        );

    }



    public function scopePermanent($query)
    {

        return $query->where(

            'dentition_type',

            'permanent'

        );

    }



    public function scopePrimary($query)
    {

        return $query->where(

            'dentition_type',

            'primary'

        );

    }



    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */


    /**
     * Update Tooth Status
     */
    public function updateStatus(

        string $status

    ): void
    {

        $this->update([

            'tooth_status'=>$status

        ]);

    }



    /**
     * Check Caries
     */
    public function hasCaries(): bool
    {

        return $this->tooth_status === 'caries';

    }



    /**
     * Get Surface Count
     */
    public function getAffectedSurfaceCountAttribute()
    {

        if(!$this->surface_data)
        {

            return 0;

        }


        return count(

            array_filter(

                $this->surface_data

            )

        );

    }



}