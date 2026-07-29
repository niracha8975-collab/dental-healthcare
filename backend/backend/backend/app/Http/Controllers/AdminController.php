<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\User;

use App\Models\Patient;

use App\Models\Appointment;

use App\Models\DentalRecord;

use App\Models\DentalTreatment;

use App\Models\AuditLog;

use Carbon\Carbon;



class AdminController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Dashboard Overview
    |--------------------------------------------------------------------------
    */


    public function dashboard()
    {


        return response()->json([


            'patients'=>Patient::count(),


            'appointments_today'=>

                Appointment::today()->count(),


            'pending_appointments'=>

                Appointment::pending()->count(),


            'completed_treatments'=>

                DentalTreatment::completed()->count(),


            'dental_records'=>

                DentalRecord::count()


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */


    public function users()
    {


        $users = User::with(

            'roles'

        )

        ->latest()

        ->paginate(20);





        return response()->json([


            'data'=>$users


        ]);


    }





    public function updateUserStatus(

        Request $request,

        User $user

    )

    {


        $request->validate([


            'status'=>'required|in:active,inactive'


        ]);





        $user->update([


            'status'=>$request->status


        ]);





        AuditLog::record(

            'update',

            'User',

            $user->id,

            [],

            [

                'status'=>$request->status

            ]

        );





        return response()->json([


            'message'=>'แก้ไขสถานะผู้ใช้งานสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Appointment Management
    |--------------------------------------------------------------------------
    */


    public function appointments(

        Request $request

    )

    {


        $appointments = Appointment::with([


            'patient',

            'service'


        ])

        ->when(

            $request->date,

            function($query)

            use($request){


                $query->whereDate(

                    'appointment_date',

                    $request->date

                );


            }

        )

        ->latest()

        ->paginate(30);





        return response()->json([


            'data'=>$appointments


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Queue Management
    |--------------------------------------------------------------------------
    */


    public function todayQueue()
    {


        $queue = Appointment::today()

            ->with([

                'patient',

                'service'

            ])

            ->orderBy(

                'queue_number'

            )

            ->get();





        return response()->json([


            'data'=>$queue


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */


    public function statistics(

        Request $request

    )

    {


        $year = $request->year ??

            now()->year;





        return response()->json([


            'year'=>$year,


            'monthly_appointments'=>

                Appointment::selectRaw(

                    'MONTH(appointment_date) month,
                     COUNT(*) total'

                )

                ->whereYear(

                    'appointment_date',

                    $year

                )

                ->groupBy('month')

                ->get(),



            'treatments'=>

                DentalTreatment::selectRaw(

                    'treatment_code,
                     COUNT(*) total'

                )

                ->groupBy(

                    'treatment_code'

                )

                ->get()



        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Audit Logs
    |--------------------------------------------------------------------------
    */


    public function auditLogs()
    {


        return response()->json([


            'data'=>AuditLog::with(

                'user'

            )

            ->latest()

            ->paginate(50)


        ]);


    }





}