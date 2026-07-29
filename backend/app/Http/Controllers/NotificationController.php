<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Notification;

use App\Models\User;

use App\Models\Patient;

use App\Services\FirebaseService;

use App\Services\LineNotifyService;

use App\Services\SmsService;

use App\Models\AuditLog;



class NotificationController extends Controller
{


    protected FirebaseService $firebase;

    protected LineNotifyService $line;

    protected SmsService $sms;



    public function __construct(

        FirebaseService $firebase,

        LineNotifyService $line,

        SmsService $sms

    )

    {

        $this->firebase = $firebase;

        $this->line = $line;

        $this->sms = $sms;

    }





    /*
    |--------------------------------------------------------------------------
    | User Notifications
    |--------------------------------------------------------------------------
    */


    public function index(

        Request $request

    )

    {


        $notifications = Notification::where(

            'user_id',

            $request->user()->id

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

                'nullable|exists:patients,id',


            'title'=>

                'required',


            'message'=>

                'required',


            'type'=>

                'required'


        ]);





        $notification = Notification::create([


            'patient_id'=>

                $request->patient_id,


            'user_id'=>

                $request->user_id,


            'type'=>

                $request->type,


            'title'=>

                $request->title,


            'message'=>

                $request->message,


            'status'=>'unread'


        ]);





        if($request->patient_id)

        {


            $patient = Patient::find(

                $request->patient_id

            );





            if(

                $patient &&

                $patient->user

            )

            {


                $this->push(

                    $patient->user,

                    $notification

                );


            }


        }





        AuditLog::record(

            'notification_send',

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
    | Push Notification
    |--------------------------------------------------------------------------
    */


    public function push(

        User $user,

        Notification $notification

    )

    {


        if(

            $user->fcm_token

        )

        {


            $this->firebase->send(

                $user->fcm_token,

                [

                    'title'=>

                        $notification->title,


                    'message'=>

                        $notification->message

                ]

            );


        }





        return true;


    }





    /*
    |--------------------------------------------------------------------------
    | Appointment Reminder
    |--------------------------------------------------------------------------
    */


    public function appointmentReminder(

        Patient $patient

    )

    {


        $notification = Notification::create([


            'patient_id'=>

                $patient->id,


            'type'=>'appointment',


            'title'=>

                'แจ้งเตือนนัดหมาย',


            'message'=>

                'กรุณามารับบริการตามวันนัด'


        ]);





        if($patient->user)

        {


            $this->push(

                $patient->user,

                $notification

            );


        }





        if($patient->phone)

        {


            $this->sms->send(

                $patient->phone,

                $notification->message

            );


        }





        return response()->json([


            'message'=>'ส่งแจ้งเตือนนัดหมายแล้ว'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Queue Calling Notification
    |--------------------------------------------------------------------------
    */


    public function queueCall(

        Request $request

    )

    {


        $notification = Notification::create([


            'patient_id'=>

                $request->patient_id,


            'type'=>'queue',


            'title'=>

                'ถึงคิวรับบริการ',


            'message'=>

                'กรุณาเข้าห้องบริการ'


        ]);





        return response()->json([


            'message'=>'แจ้งเรียกคิวสำเร็จ',


            'data'=>$notification


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


            'status'=>'read',


            'read_at'=>now()


        ]);





        return response()->json([


            'message'=>'อ่านแล้ว'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Delete
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