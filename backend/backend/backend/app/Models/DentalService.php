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

        'default_queue_limit',

        'is_active',

        'sort_order'


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


            'is_active'=>'boolean'


        ];


    }





    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */



    /**
     * Appointment Slots
     */

    public function appointmentSlots()
    {


        return $this->hasMany(

            AppointmentSlot::class,

            'service_id'

        );


    }





    /**
     * Appointments
     */

    public function appointments()
    {


        return $this->hasMany(

            Appointment::class,

            'service_id'

        );


    }





    /**
     * Treatments
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

            'is_active',

            true

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



    public function isAvailable()
    {


        return $this->is_active;


    }





    public function getFormattedPriceAttribute()
    {


        return number_format(

            $this->price,

            2

        );


    }





    public function getCategoryNameAttribute()
    {


        return match(

            $this->category

        ){


            'examination'=>'ตรวจและวินิจฉัย',


            'preventive'=>'ทันตกรรมป้องกัน',


            'restorative'=>'ทันตกรรมบูรณะ',


            'surgery'=>'ศัลยกรรมช่องปาก',


            'periodontal'=>'ปริทันต์',


            'pediatric'=>'ทันตกรรมเด็ก',


            default=>'อื่นๆ'


        };


    }


}