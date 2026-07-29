<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Appointment;

use App\Models\AppointmentSlot;

use App\Models\DentalService;

use App\Models\Notification;

use Illuminate\Support\Facades\DB;



class AppointmentController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Get Services
    |--------------------------------------------------------------------------
    */


    public function services()
    {


        return response()->json([


            'data'=>DentalService::active()

                ->orderBy(

                    'sort_order'

                )

                ->get()


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Available Slots
    |--------------------------------------------------------------------------
    */


    public function availableSlots(

        Request $request

    )

    {


        $request->validate([


            'service_id'=>'required|exists:dental_services,id',


            'date'=>'required|date'


        ]);





        $slots = AppointmentSlot::available()

            ->where(

                'service_id',

                $request->service_id

            )

            ->whereDate(

                'date',

                $request->date

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


            'service_id'=>'required|exists:dental_services,id',


            'slot_id'=>'required|exists:appointment_slots,id',


            'appointment_date'=>'required|date'


        ]);





        $patient = $request

            ->user()

            ->patient;





        if(!$patient)

        {


            return response()->json([


                'message'=>'ไม่พบข้อมูลผู้รับบริการ'


            ],404);


        }





        return DB::transaction(function() use (

            $request,

            $patient

        ){



            $slot = AppointmentSlot::findOrFail(

                $request->slot_id

            );





            if(!$slot->isAvailable())

            {


                return response()->json([


                    'message'=>'คิวเต็มแล้ว'


                ],409);


            }





            $slot->increaseBooking();





            $appointment = Appointment::create([


                'patient_id'=>$patient->id,


                'service_id'=>$request->service_id,


                'slot_id'=>$slot->id,


                'appointment_date'=>$request->appointment_date,


                'appointment_time'=>$slot->start_time,


                'status'=>'pending',


                'reason'=>$request->reason


            ]);





            $appointment->generateQueueNumber();





            Notification::create([


                'user_id'=>$patient->user_id,


                'patient_id'=>$patient->id,


                'appointment_id'=>$appointment->id,


                'type'=>'appointment',


                'title'=>'สร้างการนัดหมายสำเร็จ',


                'message'=>

                    'เลขคิว '.$appointment->queue_number,


                'channel'=>'push',


                'status'=>'pending'


            ]);





            return response()->json([


                'message'=>'จองคิวสำเร็จ',


                'data'=>$appointment


            ],201);



        });


    }





    /*
    |--------------------------------------------------------------------------
    | My Appointments
    |--------------------------------------------------------------------------
    */


    public function mine(

        Request $request

    )

    {


        $patient = $request

            ->user()

            ->patient;





        return response()->json([


            'data'=>Appointment::where(

                'patient_id',

                $patient->id

            )

            ->with([

                'service',

                'slot'

            ])

            ->latest()

            ->get()


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Confirm Appointment
    |--------------------------------------------------------------------------
    */


    public function confirm(

        Appointment $appointment,

        Request $request

    )

    {


        $appointment->confirm(

            $request->user()

        );





        return response()->json([


            'message'=>'ยืนยันนัดหมายแล้ว'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Cancel Appointment
    |--------------------------------------------------------------------------
    */


    public function cancel(

        Appointment $appointment,

        Request $request

    )

    {


        $appointment->cancel(

            $request->reason

        );





        if($appointment->slot)

        {


            $appointment->slot

                ->decreaseBooking();


        }





        return response()->json([


            'message'=>'ยกเลิกนัดหมายแล้ว'


        ]);


    }


}