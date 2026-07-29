<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\DentalRecord;

use App\Models\DentalTreatment;

use App\Models\Odontogram;

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
    | Patient Dental History
    |--------------------------------------------------------------------------
    */


    public function index(

        Patient $patient

    )

    {


        $records = DentalRecord::where(

            'patient_id',

            $patient->id

        )

        ->with([


            'treatments.service',

            'odontogram',

            'media'


        ])

        ->latest()

        ->get();





        return response()->json([


            'data'=>$records


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Create Dental Visit
    |--------------------------------------------------------------------------
    */


    public function store(

        Request $request

    )

    {


        $request->validate([


            'patient_id'=>

                'required|exists:patients,id',


            'appointment_id'=>

                'nullable|exists:appointments,id'


        ]);





        $record = DentalRecord::create([


            'patient_id'=>

                $request->patient_id,


            'appointment_id'=>

                $request->appointment_id,


            'dentist_id'=>

                auth()->id(),


            'visit_date'=>

                now(),


            'status'=>'open'


        ]);





        AuditLog::record(

            'create',

            'DentalRecord',

            $record->id,

            [],

            $record->toArray()

        );





        return response()->json([


            'message'=>'สร้าง Visit สำเร็จ',


            'data'=>$record


        ],201);


    }





    /*
    |--------------------------------------------------------------------------
    | Update Diagnosis
    |--------------------------------------------------------------------------
    */


    public function diagnosis(

        Request $request,

        DentalRecord $record

    )

    {


        $request->validate([


            'diagnosis'=>'required'


        ]);





        $record->update([


            'diagnosis'=>

                $request->diagnosis,


            'chief_complaint'=>

                $request->chief_complaint,


            'treatment_plan'=>

                $request->treatment_plan


        ]);





        return response()->json([


            'message'=>'บันทึก Diagnosis สำเร็จ',


            'data'=>$record


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


        $request->validate([


            'service_id'=>

                'required',


            'tooth_number'=>

                'nullable',


            'price'=>

                'required|numeric'


        ]);





        $treatment = DentalTreatment::create([


            'dental_record_id'=>

                $record->id,


            'service_id'=>

                $request->service_id,


            'tooth_number'=>

                $request->tooth_number,


            'surface'=>

                $request->surface,


            'price'=>

                $request->price,


            'status'=>'completed'


        ]);





        return response()->json([


            'message'=>'บันทึกการรักษาสำเร็จ',


            'data'=>$treatment


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Odontogram
    |--------------------------------------------------------------------------
    */


    public function odontogram(

        Request $request,

        DentalRecord $record

    )

    {


        $odontogram = Odontogram::updateOrCreate([


            'dental_record_id'=>

                $record->id,


            'tooth_number'=>

                $request->tooth_number


        ],[


            'condition'=>

                $request->condition,


            'surface'=>

                $request->surface,


            'note'=>

                $request->note


        ]);





        return response()->json([


            'message'=>'บันทึก Dental Chart สำเร็จ',


            'data'=>$odontogram


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Close Visit
    |--------------------------------------------------------------------------
    */


    public function complete(

        DentalRecord $record

    )

    {


        $record->update([


            'status'=>'completed',


            'completed_at'=>now()


        ]);





        return response()->json([


            'message'=>'ปิด Visit สำเร็จ'


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


        $response = $this->myPCU

            ->sendDentalRecord(

                $record

            );





        $record->update([


            'synced_at'=>now()


        ]);





        return response()->json([


            'message'=>'Sync เวชระเบียนสำเร็จ',


            'response'=>$response


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */


    public function summary(

        Patient $patient

    )

    {


        return response()->json([


            'total_visit'=>

                DentalRecord::where(

                    'patient_id',

                    $patient->id

                )->count(),



            'total_treatment'=>

                DentalTreatment::whereHas(

                    'record',

                    function($q) use($patient){


                        $q->where(

                            'patient_id',

                            $patient->id

                        );


                    }

                )->count()



        ]);


    }


}