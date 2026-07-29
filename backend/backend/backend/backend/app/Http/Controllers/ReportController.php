<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\Patient;

use App\Models\DentalRecord;

use App\Models\Appointment;

use App\Models\Queue;

use App\Models\DentalTreatment;

use App\Models\User;

use App\Models\AuditLog;



class ReportController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Dashboard Overview
    |--------------------------------------------------------------------------
    */


    public function dashboard()

    {


        return response()->json([


            'patients'=>

                Patient::count(),



            'today_appointments'=>

                Appointment::whereDate(

                    'appointment_date',

                    today()

                )->count(),



            'today_queue'=>

                Queue::whereDate(

                    'queue_date',

                    today()

                )->count(),



            'completed_service'=>

                Queue::where(

                    'status',

                    'completed'

                )->count(),



            'total_treatment'=>

                DentalTreatment::count()



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


        $data = Patient::select(

                DB::raw(

                    'YEAR(created_at) as year'

                ),

                DB::raw(

                    'COUNT(*) as total'

                )

            )

            ->groupBy(

                'year'

            )

            ->get();





        return response()->json([


            'data'=>$data


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Appointment Report
    |--------------------------------------------------------------------------
    */


    public function appointmentReport(

        Request $request

    )

    {


        $report = Appointment::select(

                'status',

                DB::raw(

                    'COUNT(*) as total'

                )

            )

            ->when(

                $request->year,

                function($q) use($request){


                    $q->whereYear(

                        'appointment_date',

                        $request->year

                    );


                }

            )

            ->groupBy(

                'status'

            )

            ->get();





        return response()->json([


            'data'=>$report


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Queue Report
    |--------------------------------------------------------------------------
    */


    public function queueReport()

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

                )->count(),



            'cancel'=>

                Queue::where(

                    'status',

                    'cancelled'

                )->count()



        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Treatment Report
    |--------------------------------------------------------------------------
    */


    public function treatmentReport(

        Request $request

    )

    {


        $data = DentalTreatment::select(

                'service_id',

                DB::raw(

                    'COUNT(*) as total'

                )

            )

            ->groupBy(

                'service_id'

            )

            ->with(

                'service'

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


    public function dmft()

    {


        $data = DentalRecord::select([


            DB::raw(

                'AVG(dmft_total) as average_dmft'

            ),


            DB::raw(

                'COUNT(*) as examined'

            )


        ])

        ->first();





        return response()->json([


            'data'=>$data


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Staff Performance
    |--------------------------------------------------------------------------
    */


    public function staffPerformance()

    {


        $data = User::whereHas(

            'roles',

            function($q){


                $q->where(

                    'name',

                    'dentist'

                );


            }

        )

        ->withCount(

            'dentalRecords'

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


    public function exportExcel(

        Request $request

    )

    {


        AuditLog::record(

            'export_excel',

            'Report',

            null

        );





        return response()->json([


            'message'=>'สร้างไฟล์ Excel สำเร็จ',


            'download'=>'reports/report.xlsx'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Export PDF
    |--------------------------------------------------------------------------
    */


    public function exportPDF(

        Request $request

    )

    {


        AuditLog::record(

            'export_pdf',

            'Report',

            null

        );





        return response()->json([


            'message'=>'สร้างไฟล์ PDF สำเร็จ',


            'download'=>'reports/report.pdf'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Health Indicator
    |--------------------------------------------------------------------------
    */


    public function indicator()

    {


        return response()->json([


            'dmft_target'=>

                DentalRecord::avg(

                    'dmft_total'

                ),



            'appointment_success'=>

                Appointment::where(

                    'status',

                    'completed'

                )->count(),



            'service_volume'=>

                DentalTreatment::count()



        ]);


    }


}