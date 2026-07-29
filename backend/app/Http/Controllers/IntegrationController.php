<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Services\MyPCUService;

use App\Services\HOSxPService;

use App\Models\Patient;

use App\Models\Appointment;

use App\Models\DentalRecord;

use App\Models\SyncLog;

use App\Models\AuditLog;



class IntegrationController extends Controller
{


    protected MyPCUService $myPCU;

    protected HOSxPService $hosxp;



    public function __construct(

        MyPCUService $myPCU,

        HOSxPService $hosxp

    )

    {

        $this->myPCU = $myPCU;

        $this->hosxp = $hosxp;

    }





    /*
    |--------------------------------------------------------------------------
    | Connection Status
    |--------------------------------------------------------------------------
    */


    public function status()

    {


        return response()->json([


            'mypcu'=>

                $this->myPCU->ping(),



            'hosxp'=>

                $this->hosxp->ping(),



            'server'=>true


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Sync Patient
    |--------------------------------------------------------------------------
    */


    public function syncPatient(

        Request $request

    )

    {


        $request->validate([


            'cid'=>'required|digits:13'


        ]);





        $log = SyncLog::create([


            'type'=>'patient',


            'reference'=>

                $request->cid,


            'status'=>'processing'


        ]);





        try {


            $data = $this->myPCU

                ->getPatient(

                    $request->cid

                );





            if($data)

            {


                Patient::updateOrCreate(

                    [

                        'cid'=>

                            $request->cid

                    ],

                    [

                        'first_name'=>

                            $data['first_name'] ?? null,


                        'last_name'=>

                            $data['last_name'] ?? null,


                        'birth_date'=>

                            $data['birth_date'] ?? null


                    ]

                );


            }





            $log->update([


                'status'=>'success',


                'response'=>$data


            ]);





            return response()->json([


                'message'=>'Sync Patient สำเร็จ',


                'data'=>$data


            ]);



        } catch(\Exception $e){



            $log->update([


                'status'=>'failed',


                'error'=>$e->getMessage()


            ]);





            return response()->json([


                'message'=>'Sync ไม่สำเร็จ'


            ],500);


        }


    }





    /*
    |--------------------------------------------------------------------------
    | Sync Appointment
    |--------------------------------------------------------------------------
    */


    public function syncAppointment(

        Request $request

    )

    {


        $appointments = $this->myPCU

            ->getAppointments(

                $request->cid

            );





        foreach($appointments as $item)

        {


            Appointment::updateOrCreate(

                [

                    'external_id'=>

                        $item['id']

                ],

                [

                    'patient_id'=>

                        $item['patient_id'],


                    'appointment_date'=>

                        $item['date'],


                    'status'=>

                        $item['status']

                ]

            );


        }





        return response()->json([


            'message'=>'Sync Appointment สำเร็จ',


            'total'=>count($appointments)


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Sync Dental Record
    |--------------------------------------------------------------------------
    */


    public function syncDentalRecord(

        DentalRecord $record

    )

    {


        $result = $this->hosxp

            ->sendDentalRecord(

                $record

            );





        SyncLog::create([


            'type'=>'dental_record',


            'reference'=>

                $record->id,


            'status'=>'success',


            'response'=>$result


        ]);





        return response()->json([


            'message'=>'ส่งเวชระเบียนสำเร็จ',


            'data'=>$result


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Import Data
    |--------------------------------------------------------------------------
    */


    public function import(

        Request $request

    )

    {


        $result = $this->myPCU

            ->import(

                $request->type

            );





        AuditLog::record(

            'import_data',

            'Integration',

            null

        );





        return response()->json([


            'message'=>'Import สำเร็จ',


            'data'=>$result


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Sync History
    |--------------------------------------------------------------------------
    */


    public function history()

    {


        return response()->json([


            'data'=>

                SyncLog::latest()

                    ->paginate(20)


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Retry Failed Sync
    |--------------------------------------------------------------------------
    */


    public function retry(

        SyncLog $log

    )

    {


        $log->update([


            'status'=>'retry'


        ]);





        return response()->json([


            'message'=>'ส่ง Sync ใหม่แล้ว'


        ]);


    }


}