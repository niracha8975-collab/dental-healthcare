<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\DentalRecord;

use App\Models\Patient;

use App\Models\Odontogram;

use App\Models\DentalTreatment;

use App\Models\AuditLog;

use Illuminate\Support\Facades\DB;



class DentalRecordController extends Controller
{


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

            'dentist',

            'treatments',

            'odontograms'

        ])

        ->latest('visit_date')

        ->get();





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


            'patient_id'=>'required|exists:patients,id',


            'visit_date'=>'required|date',


            'diagnosis'=>'nullable|string',


            'ohsp_code'=>'nullable|string'


        ]);





        $record = DB::transaction(function() use(

            $request

        ){



            $record = DentalRecord::create([


                'patient_id'=>$request->patient_id,


                'appointment_id'=>$request->appointment_id,


                'dentist_id'=>$request->user()->id,


                'visit_date'=>$request->visit_date,


                'chief_complaint'=>$request->chief_complaint,


                'diagnosis'=>$request->diagnosis,


                'treatment_plan'=>$request->treatment_plan,


                'ohsp_code'=>$request->ohsp_code,


                'clinical_note'=>$request->clinical_note


            ]);





            AuditLog::record(

                'create',

                'DentalRecord',

                $record->id,

                [],

                $record->toArray()

            );





            return $record;


        });





        return response()->json([


            'message'=>'บันทึกเวชระเบียนสำเร็จ',


            'data'=>$record


        ],201);


    }





    /*
    |--------------------------------------------------------------------------
    | Update Record
    |--------------------------------------------------------------------------
    */


    public function update(

        Request $request,

        DentalRecord $record

    )

    {


        $old = $record->toArray();





        $record->update(

            $request->all()

        );





        AuditLog::record(

            'update',

            'DentalRecord',

            $record->id,

            $old,

            $record->toArray()

        );





        return response()->json([


            'message'=>'แก้ไขข้อมูลสำเร็จ',


            'data'=>$record


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | DMFT Calculation
    |--------------------------------------------------------------------------
    */


    public function calculateDMFT(

        DentalRecord $record

    )

    {


        $dmft = $record->calculateDMFT();





        return response()->json([


            'dmft'=>$dmft


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


        $request->validate([


            'teeth'=>'required|array'


        ]);





        foreach(

            $request->teeth as $tooth

        )

        {


            Odontogram::updateOrCreate([


                'dental_record_id'=>$record->id,


                'tooth_number'=>$tooth['number']


            ],[


                'patient_id'=>$record->patient_id,


                'dentition'=>$tooth['dentition'],


                'surface'=>$tooth['surface'] ?? null,


                'condition'=>$tooth['condition'],


                'diagnosis'=>$tooth['diagnosis'] ?? null,


                'treatment'=>$tooth['treatment'] ?? null


            ]);

        }





        return response()->json([


            'message'=>'บันทึก Dental Chart สำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Add Treatment
    |--------------------------------------------------------------------------
    */


    public function addTreatment(

        Request $request

    )

    {


        $treatment = DentalTreatment::create([


            'patient_id'=>$request->patient_id,


            'dental_record_id'=>$request->dental_record_id,


            'service_id'=>$request->service_id,


            'provider_id'=>$request->user()->id,


            'treatment_code'=>$request->treatment_code,


            'tooth_number'=>$request->tooth_number,


            'procedure_detail'=>$request->procedure_detail,


            'price'=>$request->price,


            'status'=>'completed',


            'treatment_date'=>now()


        ]);





        return response()->json([


            'message'=>'บันทึกหัตถการสำเร็จ',


            'data'=>$treatment


        ],201);


    }


}