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

        'record_id',

        'old_data',

        'new_data',

        'description',

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


            'old_data'=>'array',


            'new_data'=>'array'


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





    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */



    public function scopeByModule(

        $query,

        $module

    )

    {


        return $query->where(

            'module',

            $module

        );


    }





    public function scopeByAction(

        $query,

        $action

    )

    {


        return $query->where(

            'action',

            $action

        );


    }





    public function scopeToday($query)
    {


        return $query->whereDate(

            'created_at',

            today()

        );


    }





    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */



    public static function record(

        string $action,

        string $module,

        $recordId = null,

        array $oldData = [],

        array $newData = []

    )

    {


        return self::create([


            'user_id'=>auth()->id(),


            'action'=>$action,


            'module'=>$module,


            'record_id'=>$recordId,


            'old_data'=>$oldData,


            'new_data'=>$newData,


            'ip_address'=>request()->ip(),


            'user_agent'=>request()->userAgent()


        ]);


    }





    public function isCreate()
    {


        return $this->action === 'create';


    }





    public function isUpdate()
    {


        return $this->action === 'update';


    }





    public function isDelete()
    {


        return $this->action === 'delete';


    }


}