<?php

namespace App\Http\Controllers;


use App\Models\DentalRecord;

use App\Models\DentalTreatment;

use App\Models\Odontogram;

use App\Models\AuditLog;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;



class DentalRecordController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | List Dental Records
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {


        $records = DentalRecord::with([

            'patient',

            'dentist',

            'treatments',

            'odontograms'

        ]);



        if($request->patient_id)
        {

            $records->where(

                'patient_id',

                $request->patient_id

            );

        }



        return response()->json([


            'success'=>true,


            'data'=>

                $records

                ->latest()

                ->paginate(20)


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Create Examination Record
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {


        $validated = $request->validate([


            'patient_id'=>

                'required|exists:patients,id',


            'chief_complaint'=>

                'nullable|string',


            'diagnosis'=>

                'nullable|string',


            'note'=>

                'nullable|string'


        ]);



        $record = DentalRecord::create([


            ...$validated,


            'dentist_id'=>

                auth()->id(),


            'status'=>

                'in_progress'


        ]);



        AuditLog::createLog(

            'CREATE',

            'DENTAL_RECORD',

            'DentalRecord',

            $record->id

        );



        return response()->json([


            'success'=>true,


            'message'=>

                'สร้างบันทึกตรวจสำเร็จ',


            'data'=>$record


        ],201);

    }





    /*
    |--------------------------------------------------------------------------
    | Show Record
    |--------------------------------------------------------------------------
    */

    public function show(

        DentalRecord $dentalRecord

    )
    {


        $dentalRecord->load([


            'patient',

            'dentist',

            'treatments.service',

            'odontograms'


        ]);



        AuditLog::createLog(

            'VIEW',

            'DENTAL_RECORD',

            'DentalRecord',

            $dentalRecord->id

        );



        return response()->json([


            'success'=>true,


            'data'=>$dentalRecord


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Add Treatment
    |--------------------------------------------------------------------------
    */

    public function addTreatment(

        Request $request,

        DentalRecord $dentalRecord

    )
    {


        $validated = $request->validate([


            'service_id'=>

                'required|exists:dental_services,id',


            'tooth_number'=>

                'nullable|string',


            'diagnosis'=>

                'nullable|string',


            'procedure_detail'=>

                'nullable|string',


            'cost'=>

                'nullable|numeric'


        ]);



        $treatment = $dentalRecord

            ->treatments()

            ->create([


                ...$validated,


                'dentist_id'=>

                    auth()->id(),


                'status'=>

                    'completed'


            ]);



        AuditLog::createLog(

            'CREATE',

            'DENTAL_TREATMENT',

            'DentalTreatment',

            $treatment->id

        );



        return response()->json([


            'success'=>true,


            'message'=>

                'บันทึกหัตถการสำเร็จ',


            'data'=>$treatment


        ],201);

    }





    /*
    |--------------------------------------------------------------------------
    | Update Odontogram
    |--------------------------------------------------------------------------
    */

    public function updateOdontogram(

        Request $request,

        DentalRecord $dentalRecord

    )
    {


        $validated = $request->validate([


            'tooth_number'=>

                'required|string',


            'dentition_type'=>

                'required|string',


            'tooth_status'=>

                'required|string',


            'surface_data'=>

                'nullable|array'


        ]);



        $odontogram = Odontogram::updateOrCreate([


            'patient_id'=>

                $dentalRecord->patient_id,


            'dental_record_id'=>

                $dentalRecord->id,


            'tooth_number'=>

                $validated['tooth_number']


        ],$validated);



        return response()->json([


            'success'=>true,


            'message'=>

                'อัปเดตผังฟันสำเร็จ',


            'data'=>$odontogram


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Complete Record
    |--------------------------------------------------------------------------
    */

    public function complete(

        DentalRecord $dentalRecord

    )
    {


        $dentalRecord->update([


            'status'=>'completed'


        ]);



        return response()->json([


            'success'=>true,


            'message'=>

                'ปิดบันทึกการรักษาแล้ว'


        ]);

    }





}