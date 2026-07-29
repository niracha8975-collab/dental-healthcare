<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;



class AuditLog extends Model
{


    use HasFactory;



    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [


        'user_id',

        'action',

        'module',

        'record_type',

        'record_id',

        'old_values',

        'new_values',

        'ip_address',

        'user_agent'


    ];



    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {

        return [


            'old_values'=>'array',


            'new_values'=>'array',


        ];

    }



    /*
    |--------------------------------------------------------------------------
    | Relations
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



    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */


    public function scopeModule(

        $query,

        string $module

    )
    {

        return $query->where(

            'module',

            $module

        );

    }



    public function scopeAction(

        $query,

        string $action

    )
    {

        return $query->where(

            'action',

            $action

        );

    }



    public function scopeRecord(

        $query,

        string $type,

        int $id

    )
    {

        return $query

            ->where(

                'record_type',

                $type

            )

            ->where(

                'record_id',

                $id

            );

    }



    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */


    /**
     * Create Audit Log
     */
    public static function createLog(

        string $action,

        string $module,

        ?string $recordType = null,

        ?int $recordId = null,

        array $old = [],

        array $new = []

    ): self
    {

        return self::create([


            'user_id'=>auth()->id(),


            'action'=>$action,


            'module'=>$module,


            'record_type'=>$recordType,


            'record_id'=>$recordId,


            'old_values'=>$old,


            'new_values'=>$new,


            'ip_address'=>request()->ip(),


            'user_agent'=>request()->userAgent()


        ]);

    }



    /**
     * Check Update
     */
    public function isUpdate(): bool
    {

        return $this->action === 'UPDATE';

    }



}