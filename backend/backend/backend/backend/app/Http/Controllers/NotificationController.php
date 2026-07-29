<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Notification;

use App\Models\Patient;

use App\Models\Appointment;

use App\Models\Queue;

use App\Services\NotificationService;

use App\Models\AuditLog;



class NotificationController extends Controller
{


    protected NotificationService $service;



    public function __construct(

        NotificationService $service

    )

    {

        $this->service = $service;

    }





    /*
    |--------------------------------------------------------------------------
    | Notification List
    |--------------------------------------------------------------------------
    */


    public function index(Request $request)

    {


        $notifications = Notification::with(

            'patient'

        )

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


            'data'=>$notifications


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Send Notification
    |--------------------------------------------------------------------------
    */


    public function store(

        Request $request

    )

    {


        $request->validate([


            'patient_id'=>

                'required',


            'title'=>

                'required',


            'message'=>

                'required'


        ]);





        $notification = Notification::create([


            'patient_id'=>

                $request->patient_id,


            'type'=>

                $request->type ?? 'general',


            'title'=>

                $request->title,


            'message'=>

                $request->message,


            'read'=>false


        ]);





        $this->service->push(

            $notification

        );





        AuditLog::record(

            'send_notification',

            'Notification',

            $notification->id

        );





        return response()->json([


            'message'=>'ส่งแจ้งเตือนสำเร็จ',


            'data'=>$notification


        ],201);


    }





    /*
    |--------------------------------------------------------------------------
    | Appointment Reminder
    |--------------------------------------------------------------------------
    */


    public function appointmentReminder(

        Appointment $appointment

    )

    {


        $notification = Notification::create([


            'patient_id'=>

                $appointment->patient_id,


            'type'=>

                'appointment_reminder',


            'title'=>

                'แจ้งเตือนนัดหมาย',


            'message'=>

                'ท่านมีนัดหมายวันที่ '

                .$appointment->appointment_date


        ]);





        $this->service->push(

            $notification

        );





        return response()->json([


            'message'=>'ส่ง Reminder สำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Queue Alert
    |--------------------------------------------------------------------------
    */


    public function queueAlert(

        Queue $queue

    )

    {


        $notification = Notification::create([


            'patient_id'=>

                $queue->patient_id,


            'type'=>

                'queue',


            'title'=>

                'ถึงคิวรับบริการ',


            'message'=>

                'กรุณาเข้ารับบริการ คิว '

                .$queue->queue_code


        ]);





        $this->service->push(

            $notification

        );





        return response()->json([


            'message'=>'แจ้งคิวสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Bulk Notification
    |--------------------------------------------------------------------------
    */


    public function bulk(

        Request $request

    )

    {


        $patients = Patient::whereIn(

            'id',

            $request->patients

        )

        ->get();





        foreach($patients as $patient)

        {


            Notification::create([


                'patient_id'=>

                    $patient->id,


                'type'=>

                    'announcement',


                'title'=>

                    $request->title,


                'message'=>

                    $request->message


            ]);


        }





        return response()->json([


            'message'=>'ส่งประกาศสำเร็จ',


            'total'=>$patients->count()


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Mark Read
    |--------------------------------------------------------------------------
    */


    public function read(

        Notification $notification

    )

    {


        $notification->update([


            'read'=>true,


            'read_at'=>now()


        ]);





        return response()->json([


            'message'=>'อ่านแล้ว'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Unread Count
    |--------------------------------------------------------------------------
    */


    public function unread(

        Patient $patient

    )

    {


        return response()->json([


            'count'=>

                Notification::where(

                    'patient_id',

                    $patient->id

                )

                ->where(

                    'read',

                    false

                )

                ->count()


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Delete Notification
    |--------------------------------------------------------------------------
    */


    public function destroy(

        Notification $notification

    )

    {


        $notification->delete();





        return response()->json([


            'message'=>'ลบแจ้งเตือนสำเร็จ'


        ]);


    }


}