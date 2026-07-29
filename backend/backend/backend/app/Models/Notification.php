<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;



class Notification extends Model
{


    use HasFactory;



    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */


    protected $fillable = [


        'user_id',

        'patient_id',

        'appointment_id',

        'type',

        'title',

        'message',

        'data',

        'is_read',

        'read_at',

        'sent_at',

        'channel',

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


            'data'=>'array',


            'is_read'=>'boolean',


            'read_at'=>'datetime',


            'sent_at'=>'datetime'


        ];


    }





    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */



    /**
     * User
     */

    public function user()
    {


        return $this->belongsTo(

            User::class

        );


    }





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
     * Appointment
     */

    public function appointment()
    {


        return $this->belongsTo(

            Appointment::class

        );


    }





    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */



    public function scopeUnread($query)
    {


        return $query->where(

            'is_read',

            false

        );


    }





    public function scopeSent($query)
    {


        return $query->where(

            'status',

            'sent'

        );


    }





    public function scopePending($query)
    {


        return $query->where(

            'status',

            'pending'

        );


    }





    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */



    public function markAsRead()
    {


        $this->update([


            'is_read'=>true,


            'read_at'=>now()


        ]);


    }





    public function markAsSent()
    {


        $this->update([


            'status'=>'sent',


            'sent_at'=>now()


        ]);


    }





    public function markAsFailed()
    {


        $this->update([


            'status'=>'failed'


        ]);


    }





    public function isUnread()
    {


        return !$this->is_read;


    }





    public function getNotificationIconAttribute()
    {


        return match(

            $this->type

        ){


            'appointment'=>'calendar',


            'queue'=>'clock',


            'system'=>'settings',


            'promotion'=>'megaphone',


            default=>'bell'


        };


    }


}