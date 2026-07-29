<?php

namespace App\Http\Controllers;


use App\Models\Appointment;

use App\Models\AppointmentSlot;

use App\Models\DentalService;

use App\Models\Notification;

use App\Models\AuditLog;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;



class AppointmentController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | List Appointments
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {


        $appointments = Appointment::with([

            'patient',

            'slot',

        ]);



        if($request->date)
        {

            $appointments->whereDate(

                'appointment_date',

                $request->date

            );

        }



        if($request->status)
        {

            $appointments->where(

                'status',

                $request->status

            );

        }



        return response()->json([


            'success'=>true,


            'data'=>

                $appointments

                ->latest()

                ->paginate(20)


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Create Appointment
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {


        $validated = $request->validate([


            'patient_id'=>

                'required|exists:patients,id',


            'slot_id'=>

                'required|exists:appointment_slots,id',


            'service_id'=>

                'required|exists:dental_services,id',


            'reason'=>

                'nullable|string'


        ]);



        DB::transaction(function() use(

            $validated,

            &$appointment

        ){



            $slot = AppointmentSlot::findOrFail(

                $validated['slot_id']

            );



            if(!$slot->isAvailable())
            {

                abort(

                    422,

                    'ช่วงเวลานี้เต็มแล้ว'

                );

            }



            $slot->increaseQueue();



            $appointment = Appointment::create([


                'patient_id'=>

                    $validated['patient_id'],


                'slot_id'=>

                    $validated['slot_id'],


                'service_type'=>

                    $validated['service_id'],


                'appointment_code'=>

                    Appointment::generateCode(),


                'appointment_date'=>

                    $slot->service_date,


                'queue_number'=>

                    $slot->booked_count,


                'status'=>

                    'pending',


                'reason'=>

                    $validated['reason'] ?? null,


                'created_by'=>

                    auth()->id()


            ]);



        });



        Notification::create([


            'user_id'=>

                $appointment->patient->user_id,


            'title'=>

                'จองคิวสำเร็จ',


            'message'=>

                'เลขที่นัด '.$appointment->appointment_code,


            'type'=>

                'appointment',


            'status'=>

                'pending'


        ]);



        AuditLog::createLog(

            'CREATE',

            'APPOINTMENT',

            'Appointment',

            $appointment->id

        );



        return response()->json([


            'success'=>true,


            'message'=>

                'สร้างนัดหมายสำเร็จ',


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


        $appointment->confirm();



        Notification::create([


            'user_id'=>

                $appointment

                ->patient

                ->user_id,


            'title'=>

                'ยืนยันนัดหมาย',


            'message'=>

                'นัดหมายได้รับการยืนยันแล้ว',


            'type'=>

                'appointment',


            'status'=>

                'pending'


        ]);



        return response()->json([


            'success'=>true,


            'message'=>

                'ยืนยันนัดสำเร็จ'


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


        $appointment->checkIn();



        return response()->json([


            'success'=>true,


            'message'=>

                'ลงทะเบียนเข้ารับบริการแล้ว'


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Complete
    |--------------------------------------------------------------------------
    */

    public function complete(

        Appointment $appointment

    )
    {


        $appointment->complete();



        return response()->json([


            'success'=>true,


            'message'=>

                'ปิดการรับบริการแล้ว'


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Cancel
    |--------------------------------------------------------------------------
    */

    public function cancel(

        Appointment $appointment

    )
    {


        $appointment->cancel();



        return response()->json([


            'success'=>true,


            'message'=>

                'ยกเลิกนัดหมายแล้ว'


        ]);

    }





}