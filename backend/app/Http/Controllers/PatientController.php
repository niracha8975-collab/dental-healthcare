<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Patient;

use App\Models\DentalRecord;

use App\Models\Appointment;

use App\Models\AuditLog;

use App\Services\MyPCUService;



class PatientController extends Controller
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
    | Patient List
    |--------------------------------------------------------------------------
    */


    public function index(Request $request)
    {


        $patients = Patient::with([

            'user'

        ])

        ->when(

            $request->keyword,

            function($query) use($request){


                $query->where(

                    'cid',

                    'like',

                    "%{$request->keyword}%"

                )

                ->orWhere(

                    'hn',

                    'like',

                    "%{$request->keyword}%"

                )

                ->orWhere(

                    'first_name',

                    'like',

                    "%{$request->keyword}%"

                )

                ->orWhere(

                    'last_name',

                    'like',

                    "%{$request->keyword}%"

                );


            }

        )

        ->latest()

        ->paginate(20);





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


        return response()->json([


            'data'=>$patient->load([


                'user',

                'dentalRecords.treatments.service',

                'appointments.service'


            ])


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


            'cid'=>'required|digits:13|unique:patients,cid',


            'first_name'=>'required',


            'last_name'=>'required',


            'birth_date'=>'required|date'


        ]);





        $patient = Patient::create([


            'cid'=>$request->cid,


            'hn'=>Patient::generateHN(),


            'prefix'=>$request->prefix,


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

            $patient->id

        );





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


                'phone',

                'address',

                'occupation',

                'emergency_contact'


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


            'message'=>'แก้ไขข้อมูลผู้ป่วยสำเร็จ',


            'data'=>$patient


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


        $records = DentalRecord::where(

            'patient_id',

            $patient->id

        )

        ->with([


            'treatments.service'


        ])

        ->latest()

        ->get();





        return response()->json([


            'data'=>$records


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Appointment History
    |--------------------------------------------------------------------------
    */


    public function appointmentHistory(

        Patient $patient

    )
    {


        return response()->json([


            'data'=>

                Appointment::where(

                    'patient_id',

                    $patient->id

                )

                ->with(

                    'service'

                )

                ->latest()

                ->get()


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Search My PCU
    |--------------------------------------------------------------------------
    */


    public function searchMyPCU(

        Request $request

    )
    {


        $request->validate([


            'cid'=>'required|digits:13'


        ]);





        $data = $this->myPCU

            ->getPatient(

                $request->cid

            );





        return response()->json([


            'data'=>$data


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Sync Patient
    |--------------------------------------------------------------------------
    */


    public function sync(

        Patient $patient

    )
    {


        $data = $this->myPCU

            ->getPatient(

                $patient->cid

            );





        if($data)

        {


            $patient->update([


                'last_sync_at'=>now()


            ]);


        }





        return response()->json([


            'message'=>'Sync ข้อมูลผู้ป่วยสำเร็จ',


            'data'=>$data


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


        AuditLog::record(

            'delete',

            'Patient',

            $patient->id

        );





        $patient->delete();





        return response()->json([


            'message'=>'ลบข้อมูลผู้ป่วยสำเร็จ'


        ]);


    }


}