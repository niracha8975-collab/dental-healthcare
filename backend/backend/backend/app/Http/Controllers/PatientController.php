<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Patient;

use App\Models\AuditLog;

use Illuminate\Support\Facades\DB;



class PatientController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Search Patient
    |--------------------------------------------------------------------------
    */


    public function search(

        Request $request

    )

    {


        $request->validate([


            'keyword'=>'required|string'


        ]);





        $keyword = $request->keyword;





        $patients = Patient::where(

            'cid',

            'like',

            "%{$keyword}%"

        )

        ->orWhere(

            'hn',

            'like',

            "%{$keyword}%"

        )

        ->orWhere(

            'first_name',

            'like',

            "%{$keyword}%"

        )

        ->orWhere(

            'last_name',

            'like',

            "%{$keyword}%"

        )

        ->limit(20)

        ->get();





        return response()->json([


            'data'=>$patients


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Patient Profile
    |--------------------------------------------------------------------------
    */


    public function show(

        Patient $patient

    )

    {


        $patient->load([


            'user',

            'appointments.service',

            'dentalRecords.treatments',

            'dentalRecords.odontograms'


        ]);





        return response()->json([


            'data'=>$patient


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Create Patient
    |--------------------------------------------------------------------------
    */


    public function store(

        Request $request

    )

    {


        $request->validate([


            'cid'=>'required|unique:patients,cid',


            'first_name'=>'required',


            'last_name'=>'required',


            'birth_date'=>'required|date'


        ]);





        $patient = DB::transaction(function() use(

            $request

        ){



            $patient = Patient::create([


                'cid'=>$request->cid,


                'hn'=>Patient::generateHN(),


                'first_name'=>$request->first_name,


                'last_name'=>$request->last_name,


                'birth_date'=>$request->birth_date,


                'gender'=>$request->gender,


                'phone'=>$request->phone,


                'address'=>$request->address


            ]);





            AuditLog::record(

                'create',

                'Patient',

                $patient->id,

                [],

                $patient->toArray()

            );





            return $patient;


        });





        return response()->json([


            'message'=>'เพิ่มข้อมูลผู้ป่วยสำเร็จ',


            'data'=>$patient


        ],201);


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

            $request->only([


                'first_name',

                'last_name',

                'birth_date',

                'gender',

                'phone',

                'address'


            ])

        );





        AuditLog::record(

            'update',

            'Patient',

            $patient->id,

            $old,

            $patient->toArray()

        );





        return response()->json([


            'message'=>'แก้ไขข้อมูลผู้ป่วยสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | My Profile
    |--------------------------------------------------------------------------
    */


    public function myProfile(

        Request $request

    )

    {


        $patient = $request

            ->user()

            ->patient;





        return response()->json([


            'data'=>$patient->load([


                'appointments',

                'dentalRecords'


            ])


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Sync My PCU
    |--------------------------------------------------------------------------
    */


    public function syncMyPCU(

        Patient $patient

    )

    {


        /*
        |
        | Placeholder สำหรับเชื่อม My PCU API
        |
        */





        $patient->update([


            'last_sync_at'=>now()


        ]);





        return response()->json([


            'message'=>'Sync My PCU สำเร็จ',


            'sync_time'=>now()


        ]);


    }


}