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

        'title',

        'message',

        'type',

        'reference_type',

        'reference_id',

        'data',

        'read_at',

        'sent_at',

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


            'read_at'=>'datetime',


            'sent_at'=>'datetime',


        ];

    }



    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */


    /**
     * Receiver
     */
    public function user()
    {

        return $this->belongsTo(

            User::class

        );

    }



    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */


    public function scopeUnread($query)
    {

        return $query->whereNull(

            'read_at'

        );

    }



    public function scopePending($query)
    {

        return $query->where(

            'status',

            'pending'

        );

    }



    public function scopeType(

        $query,

        string $type

    )
    {

        return $query->where(

            'type',

            $type

        );

    }



    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */


    /**
     * Mark As Read
     */
    public function markAsRead(): void
    {

        $this->update([

            'read_at'=>now()

        ]);

    }



    /**
     * Mark Sent
     */
    public function markAsSent(): void
    {

        $this->update([

            'status'=>'sent',

            'sent_at'=>now()

        ]);

    }



    /**
     * Mark Failed
     */
    public function markAsFailed(

        ?string $message = null

    ): void
    {

        $this->update([

            'status'=>'failed',

            'data'=>[

                'error'=>$message

            ]

        ]);

    }



    /**
     * Check Read
     */
    public function isRead(): bool
    {

        return !is_null(

            $this->read_at

        );

    }



}