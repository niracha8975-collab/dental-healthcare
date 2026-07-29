<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;



class AppointmentSlot extends Model
{


    use HasFactory;



    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */


    protected $fillable = [


        'service_id',

        'created_by',

        'date',

        'start_time',

        'end_time',

        'capacity',

        'booked_count',

        'status',

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


            'date'=>'date',


            'capacity'=>'integer',


            'booked_count'=>'integer'


        ];


    }





    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */



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

            Appointment::class,

            'slot_id'

        );


    }





    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */



    public function scopeAvailable($query)
    {


        return $query

            ->where(

                'status',

                'open'

            )

            ->whereColumn(

                'booked_count',

                '<',

                'capacity'

            );


    }





    public function scopeToday($query)
    {


        return $query->whereDate(

            'date',

            today()

        );


    }





    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */



    public function remainingQueue()
    {


        return max(

            0,

            $this->capacity -

            $this->booked_count

        );


    }





    public function isAvailable()
    {


        return $this->status === 'open'

            &&

            $this->booked_count < $this->capacity;


    }





    public function increaseBooking()
    {


        if($this->isAvailable())

        {


            $this->increment(

                'booked_count'

            );


            return true;


        }



        return false;


    }





    public function decreaseBooking()
    {


        if($this->booked_count > 0)

        {


            $this->decrement(

                'booked_count'

            );


        }


    }


}