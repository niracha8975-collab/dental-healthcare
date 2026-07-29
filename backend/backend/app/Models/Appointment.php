<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;



class Appointment extends Model
{


    use HasFactory;



    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [


        'patient_id',

        'slot_id',

        'service_type',

        'appointment_code',

        'appointment_date',

        'queue_number',

        'status',

        'reason',

        'note',

        'created_by',

        'confirmed_at',

        'completed_at'


    ];



    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {

        return [


            'appointment_date' => 'date',


            'confirmed_at' => 'datetime',


            'completed_at' => 'datetime',


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
     * Appointment Slot
     */
    public function slot()
    {

        return $this->belongsTo(

            AppointmentSlot::class,

            'slot_id'

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
     * Dental Record
     */
    public function dentalRecord()
    {

        return $this->hasOne(

            DentalRecord::class

        );

    }



    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */


    /**
     * Generate Appointment Code
     */
    public static function generateCode(): string
    {

        return 'APT-' .

            now()->format('Ymd') .

            '-' .

            strtoupper(

                substr(

                    uniqid(),

                    -6

                )

            );

    }



    /**
     * Confirm Appointment
     */
    public function confirm(): void
    {

        $this->update([

            'status'=>'confirmed',

            'confirmed_at'=>now()

        ]);

    }



    /**
     * Check In
     */
    public function checkIn(): void
    {

        $this->update([

            'status'=>'checked_in'

        ]);

    }



    /**
     * Complete Service
     */
    public function complete(): void
    {

        $this->update([

            'status'=>'completed',

            'completed_at'=>now()

        ]);

    }



    /**
     * Cancel Appointment
     */
    public function cancel(): void
    {

        $this->update([

            'status'=>'cancelled'

        ]);



        if($this->slot)
        {

            $this->slot
                ->decreaseQueue();

        }

    }



    /**
     * Check Active Appointment
     */
    public function isActive(): bool
    {

        return in_array(

            $this->status,

            [

                'pending',

                'confirmed',

                'checked_in'

            ]

        );

    }



}