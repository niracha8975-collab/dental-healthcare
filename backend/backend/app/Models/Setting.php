<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;



class Setting extends Model
{


    use HasFactory;



    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'key',

        'value',

        'type',

        'group',

        'description',

        'is_public'

    ];



    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {

        return [

            'is_public'=>'boolean'

        ];

    }



    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */


    public function scopeGroup(

        $query,

        string $group

    )
    {

        return $query->where(

            'group',

            $group

        );

    }



    public function scopePublic($query)
    {

        return $query->where(

            'is_public',

            true

        );

    }



    /*
    |--------------------------------------------------------------------------
    | Static Helpers
    |--------------------------------------------------------------------------
    */


    /**
     * Get Setting Value
     */
    public static function getValue(

        string $key,

        $default = null

    )
    {

        $setting = self::where(

            'key',

            $key

        )->first();



        if(!$setting)
        {

            return $default;

        }



        return match($setting->type)
        {

            'boolean'
                => filter_var(
                    $setting->value,
                    FILTER_VALIDATE_BOOLEAN
                ),


            'number'
                => intval(
                    $setting->value
                ),


            'json'
                => json_decode(
                    $setting->value,
                    true
                ),


            default
                => $setting->value

        };

    }



    /**
     * Update Or Create Setting
     */
    public static function setValue(

        string $key,

        $value,

        string $type='string'

    ): self
    {

        return self::updateOrCreate(

            [

                'key'=>$key

            ],

            [

                'value'=>is_array($value)

                    ? json_encode($value)

                    : $value,


                'type'=>$type

            ]

        );

    }



    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */


    public function isPublic(): bool
    {

        return $this->is_public;

    }



}