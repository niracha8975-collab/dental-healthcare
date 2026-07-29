<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\Patient;

use App\Models\Appointment;

use App\Models\Queue;

use App\Models\DentalRecord;

use App\Models\DentalTreatment;

use App\Models\User;



class AdminDashboardController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Dashboard Overview
    |--------------------------------------------------------------------------
    */


    public function index()

    {


        return response()->json([


            'patients'=>

                Patient::count(),



            'today_patients'=>

                Appointment::whereDate(

                    'appointment_date',

                    today()

                )->count(),



            'waiting_queue'=>

                Queue::whereDate(

                    'queue_date',

                    today()

                )

                ->where(

                    'status',

                    'waiting'

                )

                ->count(),



            'completed_service'=>

                DentalRecord::where(

                    'status',

                    'completed'

                )

                ->count(),



            'staff'=>

                User::count()



        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Patient Statistics
    |--------------------------------------------------------------------------
    */


    public function patientStatistics(

        Request $request

    )

    {


        $year = $request->year ?? now()->year;





        $data = Patient::select(

                DB::raw(

                    'MONTH(created_at) as month'

                ),

                DB::raw(

                    'COUNT(*) as total'

                )

            )

            ->whereYear(

                'created_at',

                $year

            )

            ->groupBy(

                'month'

            )

            ->orderBy(

                'month'

            )

            ->get();





        return response()->json([


            'data'=>$data


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Appointment Statistics
    |--------------------------------------------------------------------------
    */


    public function appointmentStatistics()

    {


        return response()->json([


            'pending'=>

                Appointment::where(

                    'status',

                    'pending'

                )->count(),



            'confirmed'=>

                Appointment::where(

                    'status',

                    'confirmed'

                )->count(),



            'cancelled'=>

                Appointment::where(

                    'status',

                    'cancelled'

                )->count(),



            'completed'=>

                Appointment::where(

                    'status',

                    'completed'

                )->count()


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Queue Statistics
    |--------------------------------------------------------------------------
    */


    public function queueStatistics()

    {


        return response()->json([


            'waiting'=>

                Queue::where(

                    'status',

                    'waiting'

                )->count(),



            'serving'=>

                Queue::where(

                    'status',

                    'serving'

                )->count(),



            'completed'=>

                Queue::where(

                    'status',

                    'completed'

                )->count()



        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Dental Service Statistics
    |--------------------------------------------------------------------------
    */


    public function dentalStatistics()

    {


        $data = DentalTreatment::select(

                'service_id',

                DB::raw(

                    'COUNT(*) as total'

                )

            )

            ->with(

                'service'

            )

            ->groupBy(

                'service_id'

            )

            ->get();





        return response()->json([


            'data'=>$data


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Revenue Report
    |--------------------------------------------------------------------------
    */


    public function revenue()

    {


        $total = DentalTreatment::sum(

            'price'

        );





        $monthly = DentalTreatment::select(

                DB::raw(

                    'MONTH(created_at) as month'

                ),

                DB::raw(

                    'SUM(price) as total'

                )

            )

            ->groupBy(

                'month'

            )

            ->get();





        return response()->json([


            'total'=>$total,


            'monthly'=>$monthly


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Staff Statistics
    |--------------------------------------------------------------------------
    */


    public function staffStatistics()

    {


        return response()->json([


            'total_staff'=>

                User::count(),



            'admins'=>

                User::role(

                    'admin'

                )->count(),



            'dentists'=>

                User::role(

                    'dentist'

                )->count(),



            'staff'=>

                User::role(

                    'staff'

                )->count()



        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Realtime Dashboard
    |--------------------------------------------------------------------------
    */


    public function realtime()

    {


        return response()->json([


            'current_queue'=>

                Queue::where(

                    'status',

                    'serving'

                )

                ->latest()

                ->first(),



            'waiting'=>

                Queue::where(

                    'status',

                    'waiting'

                )

                ->count(),



            'online_users'=>

                User::where(

                    'last_activity',

                    '>=',

                    now()->subMinutes(5)

                )

                ->count()



        ]);


    }


}