<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\Appointment;

use App\Models\AppointmentSlot;

use App\Models\DentalService;

use App\Models\Patient;

use App\Models\Queue;

use App\Models\Notification;

use App\Models\AuditLog;

use App\Services\MyPCUService;



class AppointmentController extends Controller
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
    | Appointment List
    |--------------------------------------------------------------------------
    */


    public function index(Request $request)
    {


        $appointments = Appointment::with([


            'patient',

            'service',

            'slot'


        ])

        ->when(

            $request->patient_id,

            function($query) use($request){


                $query->where(

                    'patient_id',

                    $request->patient_id

                );


            }

        )

        ->when(

            $request->date,

            function($query) use($request){


                $query->whereDate(

                    'appointment_date',

                    $request->date

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
    | Create Appointment
    |--------------------------------------------------------------------------
    */


    public function store(Request $request)
    {


        $request->validate([


            'patient_id'=>

                'required|exists:patients,id',


            'service_id'=>

                'required|exists:dental_services,id',


            'slot_id'=>

                'required|exists:appointment_slots,id'


        ]);





        $slot = AppointmentSlot::lockForUpdate()

            ->findOrFail(

                $request->slot_id

            );





        if(

            $slot->booked >= $slot->capacity

        )

        {


            return response()->json([


                'message'=>'ช่วงเวลานี้เต็มแล้ว'


            ],409);


        }





        $appointment = DB::transaction(function()

            use($request,$slot)

        {


            $appointment = Appointment::create([


                'patient_id'=>

                    $request->patient_id,


                'service_id'=>

                    $request->service_id,


                'slot_id'=>

                    $slot->id,


                'appointment_date'=>

                    $slot->date,


                'status'=>'pending'


            ]);





            $slot->increment(

                'booked'

            );





            return $appointment;


        });





        Notification::create([


            'patient_id'=>

                $appointment->patient_id,


            'type'=>'appointment',


            'title'=>'จองนัดหมายสำเร็จ',


            'message'=>

                'ระบบได้รับคำขอนัดหมายของคุณแล้ว'


        ]);





        return response()->json([


            'message'=>'สร้างนัดหมายสำเร็จ',


            'data'=>$appointment


        ],201);


    }





    /*
    |--------------------------------------------------------------------------
    | Show Appointment
    |--------------------------------------------------------------------------
    */


    public function show(

        Appointment $appointment

    )

    {


        return response()->json([


            'data'=>$appointment->load([


                'patient',

                'service',

                'slot'


            ])


        ]);


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

                'นัดหมายของคุณได้รับการยืนยันแล้ว'


        ]);





        return response()->json([


            'message'=>'ยืนยันนัดหมายสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Reschedule
    |--------------------------------------------------------------------------
    */


    public function reschedule(

        Request $request,

        Appointment $appointment

    )

    {


        $request->validate([


            'slot_id'=>

                'required|exists:appointment_slots,id'


        ]);





        $oldSlot = $appointment->slot;


        $newSlot = AppointmentSlot::findOrFail(

            $request->slot_id

        );





        if(

            $newSlot->booked >= $newSlot->capacity

        )

        {


            return response()->json([


                'message'=>'Slot ใหม่เต็ม'


            ],409);


        }





        DB::transaction(function()

            use($appointment,$oldSlot,$newSlot)

        {


            $oldSlot->decrement(

                'booked'

            );


            $newSlot->increment(

                'booked'

            );





            $appointment->update([


                'slot_id'=>$newSlot->id,


                'appointment_date'=>$newSlot->date


            ]);


        });





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





        if($appointment->slot)

        {


            $appointment->slot

                ->decrement(

                    'booked'

                );


        }





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
    | Check In
    |--------------------------------------------------------------------------
    */


    public function checkIn(

        Appointment $appointment

    )

    {


        $appointment->update([


            'status'=>'arrived',


            'check_in_at'=>now()


        ]);





        Queue::create([


            'appointment_id'=>

                $appointment->id,


            'patient_id'=>

                $appointment->patient_id,


            'service_id'=>

                $appointment->service_id,


            'queue_date'=>

                today(),


            'status'=>'waiting'


        ]);





        return response()->json([


            'message'=>'ลงทะเบียนเข้ารับบริการสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Sync My PCU
    |--------------------------------------------------------------------------
    */


    public function sync(

        Appointment $appointment

    )

    {


        $response = $this->myPCU

            ->sendAppointment(

                $appointment

            );





        $appointment->update([


            'synced_at'=>now()


        ]);





        return response()->json([


            'message'=>'Sync นัดหมายสำเร็จ',


            'response'=>$response


        ]);


    }


}