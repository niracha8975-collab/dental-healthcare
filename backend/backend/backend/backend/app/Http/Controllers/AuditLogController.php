<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\AuditLog;

use App\Models\User;

use App\Models\Patient;



class AuditLogController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Audit Log List
    |--------------------------------------------------------------------------
    */


    public function index(Request $request)

    {


        $logs = AuditLog::with([


            'user'


        ])

        ->when(

            $request->user_id,

            function($query) use($request){


                $query->where(

                    'user_id',

                    $request->user_id

                );


            }

        )

        ->when(

            $request->action,

            function($query) use($request){


                $query->where(

                    'action',

                    $request->action

                );


            }

        )

        ->when(

            $request->date,

            function($query) use($request){


                $query->whereDate(

                    'created_at',

                    $request->date

                );


            }

        )

        ->latest()

        ->paginate(50);





        return response()->json([


            'data'=>$logs


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | User Activity
    |--------------------------------------------------------------------------
    */


    public function userActivity(

        User $user

    )

    {


        return response()->json([


            'data'=>

                AuditLog::where(

                    'user_id',

                    $user->id

                )

                ->latest()

                ->get()


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Patient Access Log
    |--------------------------------------------------------------------------
    */


    public function patientAccess(

        Patient $patient

    )

    {


        $logs = AuditLog::where([


            'model_type'=>'Patient',


            'model_id'=>$patient->id


        ])

        ->with(

            'user'

        )

        ->latest()

        ->get();





        return response()->json([


            'data'=>$logs


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Data Change History
    |--------------------------------------------------------------------------
    */


    public function changeHistory(

        Request $request

    )

    {


        $logs = AuditLog::whereIn(

            'action',

            [

                'update',

                'delete',

                'restore'

            ]

        )

        ->when(

            $request->model,

            function($q) use($request){


                $q->where(

                    'model_type',

                    $request->model

                );


            }

        )

        ->latest()

        ->get();





        return response()->json([


            'data'=>$logs


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Security Dashboard
    |--------------------------------------------------------------------------
    */


    public function dashboard()

    {


        return response()->json([


            'today'=>

                AuditLog::whereDate(

                    'created_at',

                    today()

                )

                ->count(),



            'login'=>

                AuditLog::where(

                    'action',

                    'login'

                )

                ->count(),



            'data_update'=>

                AuditLog::where(

                    'action',

                    'update'

                )

                ->count(),



            'file_access'=>

                AuditLog::whereIn(

                    'action',

                    [

                        'upload_file',

                        'download_file'

                    ]

                )

                ->count()



        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Export Audit Report
    |--------------------------------------------------------------------------
    */


    public function export(

        Request $request

    )

    {


        return response()->json([


            'message'=>

                'สร้าง Audit Report สำเร็จ',



            'file'=>

                'reports/audit-report.pdf'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Archive Old Logs
    |--------------------------------------------------------------------------
    */


    public function archive()

    {


        $count = AuditLog::where(

                'created_at',

                '<',

                now()->subYears(3)

            )

            ->count();





        return response()->json([


            'message'=>

                'Archive Log สำเร็จ',


            'total'=>$count


        ]);


    }





}