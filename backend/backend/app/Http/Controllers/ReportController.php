<?php

namespace App\Http\Controllers;


use App\Models\Patient;
use App\Models\Appointment;
use App\Models\DentalRecord;
use App\Models\DentalTreatment;

use Illuminate\Http\Request;


class ReportController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Dashboard Summary
    |--------------------------------------------------------------------------
    */

    public function dashboard(Request $request)
    {

        $date = $request->date ?? now()->toDateString();


        return response()->json([


            'success'=>true,


            'data'=>[


                'patients'=>

                    Patient::count(),



                'today_appointments'=>

                    Appointment::whereDate(

                        'appointment_date',

                        $date

                    )->count(),



                'today_completed'=>

                    Appointment::whereDate(

                        'appointment_date',

                        $date

                    )

                    ->where(

                        'status',

                        'completed'

                    )

                    ->count(),



                'treatments'=>

                    DentalTreatment::count()


            ]

        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Appointment Report
    |--------------------------------------------------------------------------
    */

    public function appointments(Request $request)
    {


        $query = Appointment::query();



        if($request->month)
        {

            $query->whereMonth(

                'appointment_date',

                $request->month

            );

        }



        if($request->year)
        {

            $query->whereYear(

                'appointment_date',

                $request->year

            );

        }



        return response()->json([


            'success'=>true,


            'data'=>[


                'total'=>

                    $query->count(),


                'completed'=>

                    $query->clone()

                    ->where(

                        'status',

                        'completed'

                    )

                    ->count(),


                'cancelled'=>

                    $query->clone()

                    ->where(

                        'status',

                        'cancelled'

                    )

                    ->count()


            ]

        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Dental Treatment Statistics
    |--------------------------------------------------------------------------
    */

    public function treatments(Request $request)
    {


        $data = DentalTreatment::selectRaw(

            'service_id, count(*) as total'

        )

        ->groupBy(

            'service_id'

        )

        ->with(

            'service'

        )

        ->get();



        return response()->json([


            'success'=>true,


            'data'=>$data


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Patient Service Summary
    |--------------------------------------------------------------------------
    */

    public function patientSummary()
    {


        return response()->json([


            'success'=>true,


            'data'=>[


                'total_patients'=>

                    Patient::count(),



                'new_this_month'=>

                    Patient::whereMonth(

                        'created_at',

                        now()->month

                    )->count()


            ]

        ]);

    }


}