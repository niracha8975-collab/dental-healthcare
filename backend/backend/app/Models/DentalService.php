<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;



class DentalService extends Model
{


    use HasFactory;



    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [


        'code',

        'name',

        'description',

        'category',

        'duration_minutes',

        'price',

        'requires_appointment',

        'status',

        'created_by'


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


            'requires_appointment'=>'boolean',


        ];

    }



    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */


    /**
     * Creator
     */
    public function creator()
    {

        return $this->belongsTo(

            User::class,

            'created_by'

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
     * Dental Treatments
     */
    public function treatments()
    {

        return $this->hasMany(

            DentalTreatment::class,

            'service_id'

        );

    }



    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */


    public function scopeActive($query)
    {

        return $query->where(

            'status',

            'active'

        );

    }



    public function scopeCategory(

        $query,

        $category

    )
    {

        return $query->where(

            'category',

            $category

        );

    }



    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */


    /**
     * Check Available Service
     */
    public function isAvailable(): bool
    {

        return $this->status === 'active';

    }



    /**
     * Get Display Duration
     */
    public function getDurationTextAttribute()
    {

        return $this->duration_minutes . ' นาที';

    }



    /**
     * Format Price
     */
    public function getFormattedPriceAttribute()
    {

        return number_format(

            $this->price,

            2

        )

        . ' บาท';

    }



}