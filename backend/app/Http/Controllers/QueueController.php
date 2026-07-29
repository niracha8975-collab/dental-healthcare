<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\Queue;

use App\Models\Patient;

use App\Models\Appointment;

use App\Models\Notification;

use App\Models\AuditLog;



class QueueController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Today Queue List
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
    | Create Queue Number
    |--------------------------------------------------------------------------
    */


    public function store(

        Request $request

    )

    {


        $request->validate([


            'patient_id'=>

                'required|exists:patients,id',


            'service_id'=>

                'required'


        ]);





        $lastQueue = Queue::whereDate(

                'queue_date',

                today()

            )

            ->max(

                'queue_number'

            );





        $number = $lastQueue

            ? $lastQueue + 1

            : 1;





        $queue = Queue::create([


            'patient_id'=>

                $request->patient_id,


            'service_id'=>

                $request->service_id,


            'queue_number'=>

                $number,


            'queue_code'=>

                'A'.str_pad(

                    $number,

                    3,

                    '0',

                    STR_PAD_LEFT

                ),


            'queue_date'=>today(),


            'status'=>'waiting'


        ]);





        AuditLog::record(

            'create_queue',

            'Queue',

            $queue->id

        );





        return response()->json([


            'message'=>'ออกคิวสำเร็จ',


            'data'=>$queue


        ],201);


    }





    /*
    |--------------------------------------------------------------------------
    | Call Next Queue
    |--------------------------------------------------------------------------
    */


    public function callNext()

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


            'status'=>'calling',


            'called_at'=>now()


        ]);





        Notification::create([


            'patient_id'=>

                $queue->patient_id,


            'type'=>'queue',


            'title'=>'ถึงคิวรับบริการ',


            'message'=>

                'กรุณาเข้ารับบริการ คิว '.$queue->queue_code


        ]);





        AuditLog::record(

            'call_queue',

            'Queue',

            $queue->id

        );





        return response()->json([


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
    | Start Service
    |--------------------------------------------------------------------------
    */


    public function start(

        Queue $queue

    )

    {


        $queue->update([


            'status'=>'serving',


            'started_at'=>now()


        ]);





        return response()->json([


            'message'=>'เริ่มให้บริการ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Complete Service
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





        return response()->json([


            'message'=>'จบบริการสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Display Screen
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

                ->latest()

                ->first(),



            'calling'=>

                Queue::where(

                    'status',

                    'calling'

                )

                ->latest()

                ->first(),



            'waiting'=>

                Queue::where(

                    'status',

                    'waiting'

                )

                ->count()



        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */


    public function statistics()

    {


        return response()->json([


            'waiting'=>

                Queue::where(

                    'status',

                    'waiting'

                )

                ->count(),



            'serving'=>

                Queue::where(

                    'status',

                    'serving'

                )

                ->count(),



            'completed'=>

                Queue::where(

                    'status',

                    'completed'

                )

                ->count(),



            'skipped'=>

                Queue::where(

                    'status',

                    'skipped'

                )

                ->count()



        ]);


    }


}