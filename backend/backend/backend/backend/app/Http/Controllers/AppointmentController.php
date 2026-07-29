<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Appointment;

use App\Models\Patient;

use App\Models\DentalService;

use App\Models\Queue;

use App\Models\Notification;

use App\Models\Schedule;

use App\Models\AuditLog;



class AppointmentController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Appointment List
    |--------------------------------------------------------------------------
    */


    public function index(Request $request)

    {


        $appointments = Appointment::with([


            'patient',

            'service',

            'dentist'


        ])

        ->when(

            $request->date,

            function($query) use($request){


                $query->whereDate(

                    'appointment_date',

                    $request->date

                );


            }

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

        ->latest()

        ->paginate(20);





        return response()->json([


            'data'=>$appointments


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Available Time Slot
    |--------------------------------------------------------------------------
    */


    public function slots(Request $request)

    {


        $request->validate([


            'date'=>'required|date',


            'dentist_id'=>'required'


        ]);





        $slots = Schedule::where(

                'dentist_id',

                $request->dentist_id

            )

            ->where(

                'day',

                date(

                    'N',

                    strtotime(

                        $request->date

                    )

                )

            )

            ->get();





        return response()->json([


            'data'=>$slots


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Create Appointment
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

                'required',


            'appointment_date'=>

                'required|date',


            'appointment_time'=>

                'required'


        ]);





        $exists = Appointment::where([


            'dentist_id'=>

                $request->dentist_id,


            'appointment_date'=>

                $request->appointment_date,


            'appointment_time'=>

                $request->appointment_time


        ])

        ->where(

            'status',

            '!=',

            'cancelled'

        )

        ->exists();





        if($exists)

        {


            return response()->json([


                'message'=>'ช่วงเวลานี้ถูกจองแล้ว'


            ],409);


        }





        $appointment = Appointment::create([


            'patient_id'=>

                $request->patient_id,


            'service_id'=>

                $request->service_id,


            'dentist_id'=>

                $request->dentist_id,


            'appointment_date'=>

                $request->appointment_date,


            'appointment_time'=>

                $request->appointment_time,


            'status'=>'pending',


            'remark'=>

                $request->remark


        ]);





        AuditLog::record(

            'create',

            'Appointment',

            $appointment->id

        );





        return response()->json([


            'message'=>'สร้างนัดหมายสำเร็จ',


            'data'=>$appointment


        ],201);


    }





    /*
    |--------------------------------------------------------------------------
    | Confirm Appointment
    |--------------------------------------------------------------------------
    */


    public function confirm(

        Appointment $appointment

    )

    {


        $appointment->update([


            'status'=>'confirmed'


        ]);





        Notification::create([


            'patient_id'=>

                $appointment->patient_id,


            'type'=>'appointment',


            'title'=>'ยืนยันนัดหมาย',


            'message'=>

                'นัดหมายวันที่ '

                .$appointment->appointment_date


        ]);





        return response()->json([


            'message'=>'ยืนยันนัดหมายสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Reschedule Appointment
    |--------------------------------------------------------------------------
    */


    public function reschedule(

        Request $request,

        Appointment $appointment

    )

    {


        $request->validate([


            'date'=>'required|date',


            'time'=>'required'


        ]);





        $appointment->update([


            'appointment_date'=>

                $request->date,


            'appointment_time'=>

                $request->time,


            'status'=>'pending'


        ]);





        return response()->json([


            'message'=>'เลื่อนนัดสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Cancel Appointment
    |--------------------------------------------------------------------------
    */


    public function cancel(

        Appointment $appointment

    )

    {


        $appointment->update([


            'status'=>'cancelled'


        ]);





        AuditLog::record(

            'cancel',

            'Appointment',

            $appointment->id

        );





        return response()->json([


            'message'=>'ยกเลิกนัดสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Create Queue From Appointment
    |--------------------------------------------------------------------------
    */


    public function createQueue(

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


            'service_id'=>

                $appointment->service_id,


            'appointment_id'=>

                $appointment->id,


            'queue_number'=>

                $number,


            'queue_code'=>

                'N'.str_pad(

                    $number,

                    3,

                    '0',

                    STR_PAD_LEFT

                ),


            'queue_date'=>today(),


            'status'=>'waiting'


        ]);





        return response()->json([


            'message'=>'สร้างคิวจากนัดสำเร็จ',


            'data'=>$queue


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Patient History
    |--------------------------------------------------------------------------
    */


    public function history(

        Patient $patient

    )

    {


        return response()->json([


            'data'=>

                Appointment::where(

                    'patient_id',

                    $patient->id

                )

                ->latest()

                ->get()


        ]);


    }


}