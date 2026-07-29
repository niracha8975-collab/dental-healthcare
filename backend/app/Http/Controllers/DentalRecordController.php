<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\DentalRecord;

use App\Models\DentalTreatment;

use App\Models\Odontogram;

use App\Models\TreatmentPlan;

use App\Models\Patient;

use App\Models\AuditLog;

use App\Services\MyPCUService;



class DentalRecordController extends Controller
{


    protected MyPCUService $myPCU;


    public function __construct(

        MyPCUService $myPCU

    )

    {

        $this->myPCU = $myPCU;

    }





    /*
    |--------------------------------------------------------------------------
    | Dental Record List
    |--------------------------------------------------------------------------
    */


    public function index(Request $request)

    {


        $records = DentalRecord::with([


            'patient',

            'dentist',

            'treatments',

            'odontogram'


        ])

        ->when(

            $request->patient_id,

            function($query) use($request){


                $query->where(

                    'patient_id',

                    $request->patient_id

                );


            }

        )

        ->latest()

        ->paginate(20);





        return response()->json([


            'data'=>$records


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Create Dental Record
    |--------------------------------------------------------------------------
    */


    public function store(

        Request $request

    )

    {


        $request->validate([


            'patient_id'=>

                'required|exists:patients,id',


            'visit_date'=>

                'required|date'


        ]);





        $record = DentalRecord::create([


            'patient_id'=>

                $request->patient_id,


            'dentist_id'=>

                auth()->id(),


            'visit_date'=>

                $request->visit_date,


            'chief_complaint'=>

                $request->chief_complaint,


            'diagnosis'=>

                $request->diagnosis,


            'note'=>

                $request->note,


            'status'=>'open'


        ]);





        AuditLog::record(

            'create',

            'DentalRecord',

            $record->id

        );





        return response()->json([


            'message'=>'สร้างเวชระเบียนสำเร็จ',


            'data'=>$record


        ],201);


    }





    /*
    |--------------------------------------------------------------------------
    | Show Record
    |--------------------------------------------------------------------------
    */


    public function show(

        DentalRecord $record

    )

    {


        return response()->json([


            'data'=>$record->load([


                'patient',

                'dentist',

                'odontogram',

                'treatments.service',

                'treatmentPlan'


            ])


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Save Odontogram
    |--------------------------------------------------------------------------
    */


    public function odontogram(

        Request $request,

        DentalRecord $record

    )

    {


        $odontogram = Odontogram::updateOrCreate(

            [

                'dental_record_id'=>$record->id

            ],

            [

                'data'=>$request->teeth,


                'updated_by'=>auth()->id()

            ]

        );





        return response()->json([


            'message'=>'บันทึก Odontogram สำเร็จ',


            'data'=>$odontogram


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | DMFT Recording
    |--------------------------------------------------------------------------
    */


    public function dmft(

        Request $request,

        DentalRecord $record

    )

    {


        $record->update([


            'dmft_d'=>

                $request->dmft_d,


            'dmft_m'=>

                $request->dmft_m,


            'dmft_f'=>

                $request->dmft_f,


            'dmft_total'=>

                $request->dmft_d +

                $request->dmft_m +

                $request->dmft_f


        ]);





        return response()->json([


            'message'=>'บันทึก DMFT สำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Treatment Plan
    |--------------------------------------------------------------------------
    */


    public function treatmentPlan(

        Request $request,

        DentalRecord $record

    )

    {


        $plan = TreatmentPlan::updateOrCreate(

            [

                'dental_record_id'=>$record->id

            ],

            [

                'plan'=>$request->plan,


                'status'=>'planned'

            ]

        );





        return response()->json([


            'message'=>'สร้างแผนการรักษาสำเร็จ',


            'data'=>$plan


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Add Treatment
    |--------------------------------------------------------------------------
    */


    public function treatment(

        Request $request,

        DentalRecord $record

    )

    {


        $treatment = DentalTreatment::create([


            'dental_record_id'=>

                $record->id,


            'service_id'=>

                $request->service_id,


            'dentist_id'=>

                auth()->id(),


            'price'=>

                $request->price,


            'remark'=>

                $request->remark


        ]);





        return response()->json([


            'message'=>'บันทึกหัตถการสำเร็จ',


            'data'=>$treatment


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Patient Dental History
    |--------------------------------------------------------------------------
    */


    public function history(

        Patient $patient

    )

    {


        return response()->json([


            'data'=>DentalRecord::where(

                'patient_id',

                $patient->id

            )

            ->with(

                'treatments.service'

            )

            ->latest()

            ->get()


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Sync My PCU
    |--------------------------------------------------------------------------
    */


    public function sync(

        DentalRecord $record

    )

    {


        $result = $this->myPCU

            ->sendDentalRecord(

                $record

            );





        $record->update([


            'synced_at'=>now()


        ]);





        return response()->json([


            'message'=>'Sync เวชระเบียนสำเร็จ',


            'data'=>$result


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Close Record
    |--------------------------------------------------------------------------
    */


    public function close(

        DentalRecord $record

    )

    {


        $record->update([


            'status'=>'completed'


        ]);





        return response()->json([


            'message'=>'ปิดเวชระเบียนสำเร็จ'


        ]);


    }


}