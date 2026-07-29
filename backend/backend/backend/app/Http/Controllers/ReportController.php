<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Patient;

use App\Models\Appointment;

use App\Models\DentalRecord;

use App\Models\DentalTreatment;

use App\Models\DentalService;

use Illuminate\Support\Facades\DB;



class ReportController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Main Dashboard Report
    |--------------------------------------------------------------------------
    */


    public function dashboard(Request $request)
    {


        $year = $request->year ?? now()->year;


        return response()->json([


            'year'=>$year,


            'total_patient'=>

                Patient::count(),


            'new_patient'=>

                Patient::whereYear(

                    'created_at',

                    $year

                )->count(),


            'appointment_total'=>

                Appointment::whereYear(

                    'appointment_date',

                    $year

                )->count(),


            'completed_service'=>

                DentalTreatment::where(

                    'status',

                    'completed'

                )->count()


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Patient Statistics
    |--------------------------------------------------------------------------
    */


    public function patients(Request $request)
    {


        $year = $request->year ?? now()->year;


        $data = Patient::selectRaw(

            'MONTH(created_at) month,
             COUNT(*) total'

        )

        ->whereYear(

            'created_at',

            $year

        )

        ->groupBy('month')

        ->orderBy('month')

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


    public function appointments(Request $request)
    {


        $year = $request->year ?? now()->year;


        $data = Appointment::selectRaw(

            'MONTH(appointment_date) month,
             COUNT(*) total'

        )

        ->whereYear(

            'appointment_date',

            $year

        )

        ->groupBy('month')

        ->orderBy('month')

        ->get();





        return response()->json([


            'data'=>$data


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Dental Treatment Report
    |--------------------------------------------------------------------------
    */


    public function treatments(Request $request)
    {


        $data = DentalTreatment::select(

            'service_id'

        )

        ->selectRaw(

            'COUNT(*) total'

        )

        ->selectRaw(

            'SUM(price) income'

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
    | DMFT Report
    |--------------------------------------------------------------------------
    */


    public function dmft(Request $request)
    {


        $data = DentalRecord::selectRaw(

            '
            AVG(dmft) average_dmft,
            COUNT(*) total_records
            '

        )

        ->when(

            $request->age,

            function($query)

            use($request){


                $query->where(

                    'age',

                    $request->age

                );


            }

        )

        ->first();





        return response()->json([


            'data'=>$data


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | OHSP 18.5 Report
    |--------------------------------------------------------------------------
    */


    public function ohsp(Request $request)
    {


        $data = DentalRecord::select(

            'ohsp_code'

        )

        ->selectRaw(

            'COUNT(*) total'

        )

        ->groupBy(

            'ohsp_code'

        )

        ->get();





        return response()->json([


            'data'=>$data


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Export Excel
    |--------------------------------------------------------------------------
    */


    public function exportExcel(Request $request)
    {


        /*
        |
        | Connect Laravel Excel Package
        |
        */



        return response()->json([


            'message'=>

                'สร้างไฟล์ Excel สำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Export PDF
    |--------------------------------------------------------------------------
    */


    public function exportPDF(Request $request)
    {


        /*
        |
        | Connect PDF Generator
        |
        */



        return response()->json([


            'message'=>

                'สร้างไฟล์ PDF สำเร็จ'


        ]);


    }


}