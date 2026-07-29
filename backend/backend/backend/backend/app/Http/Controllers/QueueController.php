<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Queue;

use App\Models\Appointment;

use App\Models\AuditLog;

use App\Models\Notification;

use Illuminate\Support\Facades\DB;



class QueueController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Today Queue
    |--------------------------------------------------------------------------
    */


    public function index(Request $request)
    {


        $queues = Queue::with([


            'patient',

            'service'


        ])

        ->whereDate(

            'queue_date',

            today()

        )

        ->orderBy(

            'queue_number'

        )

        ->get();





        return response()->json([


            'data'=>$queues


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Generate Queue Number
    |--------------------------------------------------------------------------
    */


    public function create(

        Request $request

    )

    {


        $request->validate([


            'appointment_id'=>

                'required|exists:appointments,id'


        ]);





        $appointment = Appointment::findOrFail(

            $request->appointment_id

        );





        $queue = DB::transaction(function()

            use($appointment)

        {


            $last = Queue::whereDate(

                'queue_date',

                today()

            )

            ->max(

                'queue_number'

            );





            return Queue::create([


                'appointment_id'=>

                    $appointment->id,


                'patient_id'=>

                    $appointment->patient_id,


                'service_id'=>

                    $appointment->service_id,


                'queue_number'=>

                    $last + 1,


                'queue_date'=>

                    today(),


                'status'=>'waiting'


            ]);


        });





        return response()->json([


            'message'=>'ออกเลขคิวสำเร็จ',


            'queue'=>$queue


        ],201);


    }





    /*
    |--------------------------------------------------------------------------
    | Call Next Queue
    |--------------------------------------------------------------------------
    */


    public function next()

    {


        $queue = Queue::whereDate(

            'queue_date',

            today()

        )

        ->where(

            'status',

            'waiting'

        )

        ->orderBy(

            'queue_number'

        )

        ->first();





        if(!$queue)

        {


            return response()->json([


                'message'=>'ไม่มีคิวรอ'


            ],404);


        }





        $queue->update([


            'status'=>'serving',


            'called_at'=>now()


        ]);





        Notification::create([


            'patient_id'=>$queue->patient_id,


            'type'=>'queue',


            'title'=>'ถึงคิวรับบริการ',


            'message'=>

                'กรุณาเข้ารับบริการ'


        ]);





        return response()->json([


            'message'=>'เรียกคิวสำเร็จ',


            'data'=>$queue


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Recall Queue
    |--------------------------------------------------------------------------
    */


    public function recall(

        Queue $queue

    )

    {


        $queue->update([


            'called_at'=>now()


        ]);





        return response()->json([


            'message'=>'เรียกคิวซ้ำสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Complete Queue
    |--------------------------------------------------------------------------
    */


    public function complete(

        Queue $queue

    )

    {


        $queue->update([


            'status'=>'completed',


            'completed_at'=>now()


        ]);





        AuditLog::record(

            'complete',

            'Queue',

            $queue->id

        );





        return response()->json([


            'message'=>'ปิดคิวสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Skip Queue
    |--------------------------------------------------------------------------
    */


    public function skip(

        Queue $queue

    )

    {


        $queue->update([


            'status'=>'skipped'


        ]);





        return response()->json([


            'message'=>'ข้ามคิวสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Cancel Queue
    |--------------------------------------------------------------------------
    */


    public function cancel(

        Queue $queue

    )

    {


        $queue->update([


            'status'=>'cancelled'


        ]);





        return response()->json([


            'message'=>'ยกเลิกคิวสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Display Board
    |--------------------------------------------------------------------------
    */


    public function display()

    {


        return response()->json([


            'current'=>

                Queue::where(

                    'status',

                    'serving'

                )

                ->latest(

                    'called_at'

                )

                ->first(),



            'waiting'=>

                Queue::whereDate(

                    'queue_date',

                    today()

                )

                ->where(

                    'status',

                    'waiting'

                )

                ->orderBy(

                    'queue_number'

                )

                ->limit(10)

                ->get()



        ]);


    }


}