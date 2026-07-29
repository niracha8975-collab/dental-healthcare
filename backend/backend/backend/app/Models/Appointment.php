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

        'service_id',

        'slot_id',

        'appointment_no',

        'queue_number',

        'appointment_date',

        'appointment_time',

        'status',

        'reason',

        'note',

        'confirmed_by',

        'confirmed_at',

        'cancel_reason'


    ];





    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */


    protected function casts(): array
    {


        return [


            'appointment_date'=>'date',


            'confirmed_at'=>'datetime'


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
     * Confirm Staff
     */

    public function confirmer()
    {


        return $this->belongsTo(

            User::class,

            'confirmed_by'

        );


    }





    /**
     * Notifications
     */

    public function notifications()
    {


        return $this->hasMany(

            Notification::class

        );


    }





    /**
     * Dental Records
     */

    public function dentalRecords()
    {


        return $this->hasMany(

            DentalRecord::class

        );


    }





    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */



    public function scopePending($query)
    {


        return $query->where(

            'status',

            'pending'

        );


    }





    public function scopeToday($query)
    {


        return $query->whereDate(

            'appointment_date',

            today()

        );


    }





    public function scopeConfirmed($query)
    {


        return $query->where(

            'status',

            'confirmed'

        );


    }





    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */



    public function confirm(User $user)
    {


        $this->update([


            'status'=>'confirmed',


            'confirmed_by'=>$user->id,


            'confirmed_at'=>now()


        ]);


    }





    public function cancel($reason = null)
    {


        $this->update([


            'status'=>'cancelled',


            'cancel_reason'=>$reason


        ]);


    }





    public function complete()
    {


        $this->update([


            'status'=>'completed'


        ]);


    }





    public function isPending()
    {


        return $this->status === 'pending';


    }





    public function isConfirmed()
    {


        return $this->status === 'confirmed';


    }





    public function generateQueueNumber()
    {


        if(!$this->queue_number)

        {


            $this->queue_number =

                'Q'.

                str_pad(

                    $this->id,

                    3,

                    '0',

                    STR_PAD_LEFT

                );


            $this->save();


        }



        return $this->queue_number;


    }


}