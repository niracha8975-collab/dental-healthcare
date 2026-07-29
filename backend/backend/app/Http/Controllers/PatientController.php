<?php

namespace App\Http\Controllers;


use App\Models\Patient;

use App\Models\AuditLog;

use Illuminate\Http\Request;



class PatientController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | List Patients
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {


        $patients = Patient::query();



        if($request->search)
        {

            $patients->where(function($q) use($request)
            {

                $q->where(

                    'hn',

                    'like',

                    "%{$request->search}%"

                )

                ->orWhere(

                    'citizen_id',

                    'like',

                    "%{$request->search}%"

                )

                ->orWhere(

                    'first_name',

                    'like',

                    "%{$request->search}%"

                )

                ->orWhere(

                    'last_name',

                    'like',

                    "%{$request->search}%"

                );

            });

        }



        return response()->json([


            'success'=>true,


            'data'=>

                $patients

                ->latest()

                ->paginate(20)


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Create Patient
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {


        $validated = $request->validate([


            'hn'=>'nullable|string|unique:patients',


            'citizen_id'=>

                'required|string|unique:patients',


            'first_name'=>

                'required|string',


            'last_name'=>

                'required|string',


            'birth_date'=>

                'nullable|date',


            'gender'=>

                'nullable|string',


            'phone'=>

                'nullable|string'


        ]);



        $patient = Patient::create(

            $validated

        );



        AuditLog::createLog(

            'CREATE',

            'PATIENT',

            'Patient',

            $patient->id,

            [],

            $patient->toArray()

        );



        return response()->json([


            'success'=>true,


            'message'=>

                'เพิ่มข้อมูลผู้ป่วยสำเร็จ',


            'data'=>$patient


        ],201);

    }





    /*
    |--------------------------------------------------------------------------
    | Show Patient
    |--------------------------------------------------------------------------
    */

    public function show(

        Patient $patient

    )
    {


        $patient->load([


            'appointments',


            'dentalRecords.treatments',


            'odontograms'


        ]);



        AuditLog::createLog(

            'VIEW',

            'PATIENT',

            'Patient',

            $patient->id

        );



        return response()->json([


            'success'=>true,


            'data'=>$patient


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Update Patient
    |--------------------------------------------------------------------------
    */

    public function update(

        Request $request,

        Patient $patient

    )
    {


        $old = $patient->toArray();



        $patient->update(

            $request->all()

        );



        AuditLog::createLog(

            'UPDATE',

            'PATIENT',

            'Patient',

            $patient->id,

            $old,

            $patient->fresh()->toArray()

        );



        return response()->json([


            'success'=>true,


            'message'=>

                'แก้ไขข้อมูลสำเร็จ',


            'data'=>$patient


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Delete Patient
    |--------------------------------------------------------------------------
    */

    public function destroy(

        Patient $patient

    )
    {


        AuditLog::createLog(

            'DELETE',

            'PATIENT',

            'Patient',

            $patient->id

        );



        $patient->delete();



        return response()->json([


            'success'=>true,


            'message'=>

                'ลบข้อมูลสำเร็จ'


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Dental History
    |--------------------------------------------------------------------------
    */

    public function dentalHistory(

        Patient $patient

    )
    {


        return response()->json([


            'success'=>true,


            'data'=>

                $patient

                ->dentalRecords()

                ->with('treatments')

                ->latest()

                ->get()


        ]);

    }



}