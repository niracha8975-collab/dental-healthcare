<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\Patient;

use App\Models\Appointment;

use App\Models\Queue;

use App\Models\DentalRecord;

use App\Models\DentalTreatment;

use App\Services\ReportExportService;

use App\Models\AuditLog;



class ReportController extends Controller
{


    protected ReportExportService $export;


    public function __construct(

        ReportExportService $export

    )

    {

        $this->export = $export;

    }





    /*
    |--------------------------------------------------------------------------
    | Patient Report
    |--------------------------------------------------------------------------
    */


    public function patients(

        Request $request

    )

    {


        $data = Patient::select(

                'gender',

                DB::raw(

                    'COUNT(*) as total'

                )

            )

            ->groupBy(

                'gender'

            )

            ->get();





        return response()->json([


            'data'=>$data


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Dental Service Report
    |--------------------------------------------------------------------------
    */


    public function dentalServices(

        Request $request

    )

    {


        $data = DentalTreatment::select(

                'service_id',

                DB::raw(

                    'COUNT(*) as total'

                ),

                DB::raw(

                    'SUM(price) as revenue'

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
    | Dental Record Report
    |--------------------------------------------------------------------------
    */


    public function dentalRecords(

        Request $request

    )

    {


        $records = DentalRecord::with([


            'patient',

            'dentist',

            'treatments'


        ])

        ->when(

            $request->start_date,

            function($query) use($request){


                $query->whereDate(

                    'visit_date',

                    '>=',

                    $request->start_date

                );


            }

        )

        ->when(

            $request->end_date,

            function($query) use($request){


                $query->whereDate(

                    'visit_date',

                    '<=',

                    $request->end_date

                );


            }

        )

        ->get();





        return response()->json([


            'data'=>$records


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | DMFT Report
    |--------------------------------------------------------------------------
    */


    public function dmft(

        Request $request

    )

    {


        $data = DB::table(

                'odontograms'

            )

            ->select(

                'condition',

                DB::raw(

                    'COUNT(*) as total'

                )

            )

            ->groupBy(

                'condition'

            )

            ->get();





        return response()->json([


            'data'=>$data


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Queue Report
    |--------------------------------------------------------------------------
    */


    public function queue(

    )

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
    | Revenue Report
    |--------------------------------------------------------------------------
    */


    public function revenue()

    {


        return response()->json([


            'total'=>

                DentalTreatment::sum(

                    'price'

                ),



            'monthly'=>

                DentalTreatment::select(

                    DB::raw(

                        'MONTH(created_at) month'

                    ),

                    DB::raw(

                        'SUM(price) total'

                    )

                )

                ->groupBy(

                    'month'

                )

                ->get()



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


        $file = $this->export

            ->excel(

                $request->type

            );





        AuditLog::record(

            'export_excel',

            'Report',

            null

        );





        return response()->download(

            $file

        );


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


        $file = $this->export

            ->pdf(

                $request->type

            );





        AuditLog::record(

            'export_pdf',

            'Report',

            null

        );





        return response()->download(

            $file

        );


    }


}