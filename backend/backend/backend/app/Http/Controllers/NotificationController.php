<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Notification;

use App\Models\User;

use App\Services\FirebaseNotificationService;

use Illuminate\Support\Facades\Auth;



class NotificationController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | List Notifications
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
    | Unread Notifications
    |--------------------------------------------------------------------------
    */


    public function unread(

        Request $request

    )

    {


        return response()->json([


            'data'=>Notification::where(

                'user_id',

                $request->user()->id

            )

            ->unread()

            ->latest()

            ->get()


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


        $this->authorize(

            'view',

            $notification

        );





        $notification->markAsRead();





        return response()->json([


            'message'=>'อ่านแจ้งเตือนแล้ว'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Read All
    |--------------------------------------------------------------------------
    */


    public function readAll(

        Request $request

    )

    {


        Notification::where(

            'user_id',

            $request->user()->id

        )

        ->unread()

        ->update([


            'is_read'=>true,


            'read_at'=>now()


        ]);





        return response()->json([


            'message'=>'อ่านทั้งหมดแล้ว'


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


        $this->authorize(

            'delete',

            $notification

        );





        $notification->delete();





        return response()->json([


            'message'=>'ลบแจ้งเตือนสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Send Push Notification
    |--------------------------------------------------------------------------
    */


    public function send(

        Request $request,

        FirebaseNotificationService $firebase

    )

    {


        $request->validate([


            'user_id'=>'required|exists:users,id',


            'title'=>'required',


            'message'=>'required'


        ]);





        $user = User::findOrFail(

            $request->user_id

        );





        $notification = Notification::create([


            'user_id'=>$user->id,


            'type'=>$request->type ?? 'system',


            'title'=>$request->title,


            'message'=>$request->message,


            'channel'=>'push',


            'status'=>'pending'


        ]);





        if(

            $user->firebase_token

        )

        {


            $firebase->send(

                $user->firebase_token,

                $request->title,

                $request->message

            );





            $notification->markAsSent();


        }





        return response()->json([


            'message'=>'ส่ง Notification สำเร็จ',


            'data'=>$notification


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Appointment Reminder
    |--------------------------------------------------------------------------
    */


    public function appointmentReminder()
    {


        $appointments = \App\Models\Appointment::

            tomorrow()

            ->with(

                'patient.user'

            )

            ->get();





        foreach(

            $appointments as $appointment

        )

        {


            if(

                $appointment->patient?->user

            )

            {


                Notification::create([


                    'user_id'=>$appointment

                        ->patient

                        ->user

                        ->id,


                    'patient_id'=>$appointment->patient_id,


                    'appointment_id'=>$appointment->id,


                    'type'=>'appointment',


                    'title'=>'แจ้งเตือนการนัดหมาย',


                    'message'=>

                        'คุณมีนัดหมายพรุ่งนี้',


                    'channel'=>'push',


                    'status'=>'pending'


                ]);


            }


        }





        return response()->json([


            'message'=>'สร้าง Reminder สำเร็จ'


        ]);


    }


}