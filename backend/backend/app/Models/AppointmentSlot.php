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


        'service_date',

        'start_time',

        'end_time',

        'max_queue',

        'booked_count',

        'slot_type',

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


            'service_date' => 'date',


            'start_time' => 'datetime:H:i',


            'end_time' => 'datetime:H:i',


        ];

    }



    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */


    /**
     * Created By User
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
    | Helper Methods
    |--------------------------------------------------------------------------
    */


    /**
     * Remaining Queue
     */
    public function getAvailableQueueAttribute()
    {

        return max(

            0,

            $this->max_queue - $this->booked_count

        );

    }



    /**
     * Check Slot Available
     */
    public function isAvailable(): bool
    {

        return (

            $this->status === 'available'

            &&

            $this->booked_count < $this->max_queue

        );

    }



    /**
     * Increase Queue
     */
    public function increaseQueue(): void
    {

        $this->increment(

            'booked_count'

        );


        if(

            $this->booked_count >= $this->max_queue

        ){

            $this->update([

                'status'=>'full'

            ]);

        }

    }



    /**
     * Decrease Queue
     */
    public function decreaseQueue(): void
    {

        $this->decrement(

            'booked_count'

        );


        if(

            $this->status === 'full'

        ){

            $this->update([

                'status'=>'available'

            ]);

        }

    }



}