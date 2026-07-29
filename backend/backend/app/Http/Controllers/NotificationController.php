<?php

namespace App\Http\Controllers;


use App\Models\Notification;

use App\Models\DeviceToken;

use App\Models\AuditLog;

use Illuminate\Http\Request;



class NotificationController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | List User Notifications
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {

        $notifications = Notification::where(

            'user_id',

            auth()->id()

        );


        if($request->type)
        {

            $notifications->where(

                'type',

                $request->type

            );

        }


        return response()->json([


            'success'=>true,


            'data'=>

                $notifications

                ->latest()

                ->paginate(20)


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Unread Count
    |--------------------------------------------------------------------------
    */

    public function unreadCount()
    {

        $count = Notification::where(

            'user_id',

            auth()->id()

        )

        ->whereNull(

            'read_at'

        )

        ->count();



        return response()->json([


            'success'=>true,


            'data'=>[

                'count'=>$count

            ]


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Mark Read
    |--------------------------------------------------------------------------
    */

    public function markRead(

        Notification $notification

    )
    {


        $notification->markAsRead();



        return response()->json([


            'success'=>true,


            'message'=>

                'อ่านแจ้งเตือนแล้ว'


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Mark All Read
    |--------------------------------------------------------------------------
    */

    public function markAllRead()
    {


        Notification::where(

            'user_id',

            auth()->id()

        )

        ->whereNull(

            'read_at'

        )

        ->update([


            'read_at'=>now()


        ]);



        return response()->json([


            'success'=>true,


            'message'=>

                'อ่านทั้งหมดแล้ว'


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


            'success'=>true,


            'message'=>

                'ลบแจ้งเตือนแล้ว'


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Send Push Notification
    |--------------------------------------------------------------------------
    */

    public function sendPush(Request $request)
    {


        $validated = $request->validate([


            'user_id'=>

                'required|exists:users,id',


            'title'=>

                'required|string',


            'message'=>

                'required|string'


        ]);



        $notification = Notification::create([


            'user_id'=>

                $validated['user_id'],


            'title'=>

                $validated['title'],


            'message'=>

                $validated['message'],


            'type'=>

                'system',


            'status'=>

                'pending'


        ]);



        $devices = DeviceToken::where(

            'user_id',

            $validated['user_id']

        )

        ->active()

        ->get();



        /*
         * Firebase Service จะถูกเรียกใน NotificationService
         */


        AuditLog::createLog(

            'SEND_NOTIFICATION',

            'NOTIFICATION',

            'Notification',

            $notification->id

        );



        return response()->json([


            'success'=>true,


            'message'=>

                'ส่งแจ้งเตือนสำเร็จ',


            'data'=>[


                'notification'=>$notification,


                'devices'=>$devices->count()


            ]


        ]);

    }



}