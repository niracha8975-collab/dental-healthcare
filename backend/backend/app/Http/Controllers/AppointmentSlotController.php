<?php

namespace App\Http\Controllers;


use App\Models\AppointmentSlot;

use App\Models\AuditLog;

use Illuminate\Http\Request;



class AppointmentSlotController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Available Slots
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {


        $slots = AppointmentSlot::query();



        if($request->date)
        {

            $slots->whereDate(

                'service_date',

                $request->date

            );

        }



        if($request->service_id)
        {

            $slots->where(

                'service_id',

                $request->service_id

            );

        }



        return response()->json([


            'success'=>true,


            'data'=>

                $slots

                ->available()

                ->orderBy(

                    'start_time'

                )

                ->get()


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Create Slot
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {


        $validated = $request->validate([


            'service_id'=>

                'required|exists:dental_services,id',


            'service_date'=>

                'required|date',


            'start_time'=>

                'required',


            'end_time'=>

                'required',


            'max_queue'=>

                'required|integer|min:1'


        ]);



        $slot = AppointmentSlot::create([


            ...$validated,


            'booked_count'=>0,


            'status'=>'active',


            'created_by'=>

                auth()->id()


        ]);



        AuditLog::createLog(

            'CREATE',

            'APPOINTMENT_SLOT',

            'AppointmentSlot',

            $slot->id

        );



        return response()->json([


            'success'=>true,


            'message'=>

                'สร้างช่วงเวลาให้บริการสำเร็จ',


            'data'=>$slot


        ],201);

    }





    /*
    |--------------------------------------------------------------------------
    | Show Slot
    |--------------------------------------------------------------------------
    */

    public function show(

        AppointmentSlot $appointmentSlot

    )
    {


        $appointmentSlot->load([


            'service',

            'appointments.patient'


        ]);



        return response()->json([


            'success'=>true,


            'data'=>$appointmentSlot


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Update Slot
    |--------------------------------------------------------------------------
    */

    public function update(

        Request $request,

        AppointmentSlot $appointmentSlot

    )
    {


        $old = $appointmentSlot->toArray();



        $appointmentSlot->update(

            $request->all()

        );



        AuditLog::createLog(

            'UPDATE',

            'APPOINTMENT_SLOT',

            'AppointmentSlot',

            $appointmentSlot->id,

            $old,

            $appointmentSlot->fresh()->toArray()

        );



        return response()->json([


            'success'=>true,


            'message'=>

                'แก้ไขช่วงเวลาสำเร็จ',


            'data'=>$appointmentSlot


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Toggle Status
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(

        AppointmentSlot $appointmentSlot

    )
    {


        $appointmentSlot->update([


            'status'=>

                $appointmentSlot->status === 'active'

                ? 'inactive'

                : 'active'


        ]);



        return response()->json([


            'success'=>true,


            'message'=>

                'เปลี่ยนสถานะช่วงเวลาแล้ว',


            'data'=>$appointmentSlot


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Delete Slot
    |--------------------------------------------------------------------------
    */

    public function destroy(

        AppointmentSlot $appointmentSlot

    )
    {


        if($appointmentSlot->booked_count > 0)
        {

            return response()->json([


                'success'=>false,


                'message'=>

                    'ไม่สามารถลบช่วงเวลาที่มีการจองแล้ว'


            ],422);

        }



        $appointmentSlot->delete();



        return response()->json([


            'success'=>true,


            'message'=>

                'ลบช่วงเวลาสำเร็จ'


        ]);

    }



}