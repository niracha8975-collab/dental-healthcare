<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Patient;

use App\Models\DentalRecord;

use App\Models\Appointment;

use App\Models\AuditLog;

use App\Services\MyPCUService;



class MyPCUController extends Controller
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
    | Test Connection
    |--------------------------------------------------------------------------
    */


    public function connection()

    {


        $status = $this->myPCU

            ->checkConnection();





        return response()->json([


            'connected'=>$status,


            'time'=>now()


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Sync Patient By CID
    |--------------------------------------------------------------------------
    */


    public function syncPatient(

        Request $request

    )

    {


        $request->validate([


            'cid'=>'required|digits:13'


        ]);





        $externalPatient = $this->myPCU

            ->getPatient(

                $request->cid

            );





        if(!$externalPatient)

        {


            return response()->json([


                'message'=>'ไม่พบข้อมูลจาก My PCU'


            ],404);


        }





        $patient = Patient::updateOrCreate([


            'cid'=>$request->cid


        ],[


            'hn'=>

                Patient::generateHN(),


            'first_name'=>

                $externalPatient['first_name'],


            'last_name'=>

                $externalPatient['last_name'],


            'birth_date'=>

                $externalPatient['birth_date'],


            'gender'=>

                $externalPatient['gender'],


            'phone'=>

                $externalPatient['phone'] ?? null


        ]);





        AuditLog::record(

            'sync',

            'Patient',

            $patient->id,

            [],

            [

                'source'=>'MyPCU'

            ]

        );





        return response()->json([


            'message'=>'Sync Patient สำเร็จ',


            'data'=>$patient


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Sync Health Profile
    |--------------------------------------------------------------------------
    */


    public function healthProfile(

        Patient $patient

    )

    {


        $health = $this->myPCU

            ->getHealthProfile(

                $patient->cid

            );





        return response()->json([


            'data'=>$health


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Send Dental Service Data
    |--------------------------------------------------------------------------
    */


    public function sendDentalRecord(

        DentalRecord $record

    )

    {


        $response = $this->myPCU

            ->sendDentalRecord(

                $record

            );





        AuditLog::record(

            'sync',

            'DentalRecord',

            $record->id,

            [],

            [

                'destination'=>'MyPCU'

            ]

        );





        return response()->json([


            'message'=>'ส่งข้อมูลทันตกรรมสำเร็จ',


            'response'=>$response


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Send Appointment Data
    |--------------------------------------------------------------------------
    */


    public function sendAppointment(

        Appointment $appointment

    )

    {


        $response = $this->myPCU

            ->sendAppointment(

                $appointment

            );





        return response()->json([


            'message'=>'ส่งข้อมูลนัดหมายสำเร็จ',


            'response'=>$response


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Sync Dashboard
    |--------------------------------------------------------------------------
    */


    public function syncDashboard()

    {


        return response()->json([


            'patients'=>

                Patient::whereNotNull(

                    'last_sync_at'

                )->count(),



            'records'=>

                DentalRecord::whereNotNull(

                    'synced_at'

                )->count(),



            'appointments'=>

                Appointment::whereNotNull(

                    'synced_at'

                )->count()



        ]);


    }


}