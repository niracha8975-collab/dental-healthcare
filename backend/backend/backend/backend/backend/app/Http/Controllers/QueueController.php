<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Queue;

use App\Models\Patient;

use App\Models\Appointment;

use App\Models\DentalService;

use App\Services\NotificationService;

use App\Services\AudioAnnouncementService;

use App\Models\AuditLog;



class QueueController extends Controller
{


    protected NotificationService $notification;

    protected AudioAnnouncementService $audio;



    public function __construct(

        NotificationService $notification,

        AudioAnnouncementService $audio

    )

    {

        $this->notification = $notification;

        $this->audio = $audio;

    }





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

        ->when(

            $request->status,

            function($query) use($request){


                $query->where(

                    'status',

                    $request->status

                );


            }

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
    | Create Walk-in Queue
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





        $number = Queue::whereDate(

                'queue_date',

                today()

            )

            ->max(

                'queue_number'

            ) + 1;





        $queue = Queue::create([


            'patient_id'=>

                $request->patient_id,


            'service_id'=>

                $request->service_id,


            'queue_number'=>

                $number,


            'queue_code'=>

                'A'

                .

                str_pad(

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


            'message'=>'ออกบัตรคิวสำเร็จ',


            'data'=>$queue


        ],201);


    }





    /*
    |--------------------------------------------------------------------------
    | Create Queue From Appointment
    |--------------------------------------------------------------------------
    */


    public function appointmentQueue(

        Appointment $appointment

    )

    {


        $number = Queue::whereDate(

                'queue_date',

                today()

            )

            ->max(

                'queue_number'

            ) + 1;





        $queue = Queue::create([


            'patient_id'=>

                $appointment->patient_id,


            'appointment_id'=>

                $appointment->id,


            'service_id'=>

                $appointment->service_id,


            'queue_number'=>

                $number,


            'queue_code'=>

                'N'

                .

                str_pad(

                    $number,

                    3,

                    '0',

                    STR_PAD_LEFT

                ),


            'status'=>'waiting'


        ]);





        return response()->json([


            'data'=>$queue


        ]);


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





        $this->audio->call(

            $queue->queue_code

        );





        $this->notification->queue(

            $queue

        );





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


        $this->audio->call(

            $queue->queue_code

        );





        return response()->json([


            'message'=>'เรียกคิวซ้ำสำเร็จ'


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


            'start_time'=>now()


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


            'finish_time'=>now()


        ]);





        AuditLog::record(

            'complete_queue',

            'Queue',

            $queue->id

        );





        return response()->json([


            'message'=>'เสร็จสิ้นบริการ'


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
    | Queue Display
    |--------------------------------------------------------------------------
    */


    public function display()

    {


        return response()->json([


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
    | History
    |--------------------------------------------------------------------------
    */


    public function history()

    {


        return response()->json([


            'data'=>

                Queue::whereDate(

                    'queue_date',

                    today()

                )

                ->latest()

                ->get()


        ]);


    }


}