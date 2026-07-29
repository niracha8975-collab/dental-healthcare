<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;



class MypcuSyncLog extends Model
{


    use HasFactory;



    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [


        'sync_type',

        'reference_type',

        'reference_id',

        'request_data',

        'response_data',

        'status',

        'error_message',

        'synced_at'


    ];



    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {

        return [


            'request_data'=>'array',


            'response_data'=>'array',


            'synced_at'=>'datetime',


        ];

    }



    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */


    public function scopeSuccess($query)
    {

        return $query->where(

            'status',

            'success'

        );

    }



    public function scopeFailed($query)
    {

        return $query->where(

            'status',

            'failed'

        );

    }



    public function scopeType(

        $query,

        string $type

    )
    {

        return $query->where(

            'sync_type',

            $type

        );

    }



    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */


    /**
     * Mark Success
     */
    public function markSuccess(

        array $response = []

    ): void
    {

        $this->update([


            'status'=>'success',


            'response_data'=>$response,


            'synced_at'=>now()


        ]);

    }



    /**
     * Mark Failed
     */
    public function markFailed(

        string $message

    ): void
    {

        $this->update([


            'status'=>'failed',


            'error_message'=>$message,


            'synced_at'=>now()


        ]);

    }



    /**
     * Check Success
     */
    public function isSuccess(): bool
    {

        return $this->status === 'success';

    }



}