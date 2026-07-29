<?php

namespace App\Http\Controllers;


use App\Models\AuditLog;

use Illuminate\Http\Request;



class AuditLogController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | List Audit Logs
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {


        $logs = AuditLog::with(

            'user'

        );



        if($request->user_id)
        {

            $logs->where(

                'user_id',

                $request->user_id

            );

        }



        if($request->action)
        {

            $logs->where(

                'action',

                $request->action

            );

        }



        if($request->module)
        {

            $logs->where(

                'module',

                $request->module

            );

        }



        if($request->date)
        {

            $logs->whereDate(

                'created_at',

                $request->date

            );

        }



        return response()->json([


            'success'=>true,


            'data'=>

                $logs

                ->latest()

                ->paginate(50)


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Show Detail
    |--------------------------------------------------------------------------
    */

    public function show(

        AuditLog $auditLog

    )
    {


        $auditLog->load(

            'user'

        );



        return response()->json([


            'success'=>true,


            'data'=>$auditLog


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Security Summary
    |--------------------------------------------------------------------------
    */

    public function summary()
    {


        return response()->json([


            'success'=>true,


            'data'=>[


                'today'=>

                    AuditLog::whereDate(

                        'created_at',

                        today()

                    )->count(),



                'login'=>

                    AuditLog::where(

                        'action',

                        'LOGIN'

                    )->count(),



                'data_changes'=>

                    AuditLog::whereIn(

                        'action',

                        [

                            'CREATE',

                            'UPDATE',

                            'DELETE'

                        ]

                    )->count()


            ]


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Delete Old Logs
    |--------------------------------------------------------------------------
    */

    public function cleanup(Request $request)
    {


        $days = $request->days ?? 365;



        $deleted = AuditLog::where(

            'created_at',

            '<',

            now()->subDays($days)

        )

        ->delete();



        return response()->json([


            'success'=>true,


            'message'=>

                'ลบประวัติที่หมดอายุแล้ว',


            'deleted'=>$deleted


        ]);

    }



}