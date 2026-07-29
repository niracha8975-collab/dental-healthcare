<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;



class DeviceToken extends Model
{


    use HasFactory;



    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [


        'user_id',

        'token',

        'platform',

        'device_name',

        'app_version',

        'last_active_at',

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


            'last_active_at'=>'datetime',


        ];

    }



    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */


    /**
     * Owner
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


    public function scopeActive($query)
    {

        return $query->where(

            'status',

            'active'

        );

    }



    public function scopePlatform(

        $query,

        string $platform

    )
    {

        return $query->where(

            'platform',

            $platform

        );

    }



    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */


    /**
     * Update Activity
     */
    public function updateActivity(): void
    {

        $this->update([

            'last_active_at'=>now(),

            'status'=>'active'

        ]);

    }



    /**
     * Disable Token
     */
    public function deactivate(): void
    {

        $this->update([

            'status'=>'inactive'

        ]);

    }



    /**
     * Check Active Token
     */
    public function isActive(): bool
    {

        return $this->status === 'active';

    }



}