<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\AppointmentSlot;

use App\Models\DentalService;

use App\Models\AuditLog;



class AppointmentSlotController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Available Slots
    |--------------------------------------------------------------------------
    */


    public function index(Request $request)
    {


        $request->validate([


            'date'=>'required|date'


        ]);





        $slots = AppointmentSlot::whereDate(

            'date',

            $request->date

        )

        ->with('service')

        ->orderBy(

            'start_time'

        )

        ->get();





        return response()->json([


            'data'=>$slots


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Create Slot
    |--------------------------------------------------------------------------
    */


    public function store(Request $request)
    {


        $request->validate([


            'service_id'=>'required|exists:dental_services,id',


            'date'=>'required|date',


            'start_time'=>'required',


            'end_time'=>'required',


            'capacity'=>'required|integer|min:1'


        ]);





        $slot = AppointmentSlot::create([


            'service_id'=>$request->service_id,


            'date'=>$request->date,


            'start_time'=>$request->start_time,


            'end_time'=>$request->end_time,


            'capacity'=>$request->capacity,


            'booked'=>0,


            'status'=>'open'


        ]);





        AuditLog::record(

            'create',

            'AppointmentSlot',

            $slot->id,

            [],

            $slot->toArray()

        );





        return response()->json([


            'message'=>'สร้าง Slot สำเร็จ',


            'data'=>$slot


        ],201);


    }





    /*
    |--------------------------------------------------------------------------
    | Update Slot
    |--------------------------------------------------------------------------
    */


    public function update(

        Request $request,

        AppointmentSlot $slot

    )

    {


        $old = $slot->toArray();





        $slot->update(

            $request->only([


                'start_time',

                'end_time',

                'capacity'


            ])

        );





        AuditLog::record(

            'update',

            'AppointmentSlot',

            $slot->id,

            $old,

            $slot->toArray()

        );





        return response()->json([


            'message'=>'แก้ไข Slot สำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Toggle Slot Status
    |--------------------------------------------------------------------------
    */


    public function toggle(

        AppointmentSlot $slot

    )

    {


        $slot->update([


            'status'=>

                $slot->status === 'open'

                ? 'closed'

                : 'open'


        ]);





        return response()->json([


            'message'=>'เปลี่ยนสถานะ Slot สำเร็จ',


            'status'=>$slot->status


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Generate Daily Slots
    |--------------------------------------------------------------------------
    */


    public function generate(Request $request)
    {


        $request->validate([


            'service_id'=>'required|exists:dental_services,id',


            'date'=>'required|date',


            'start'=>'required',


            'end'=>'required',


            'interval'=>'required|integer'


        ]);





        $slots = [];





        $time = strtotime(

            $request->start

        );


        $end = strtotime(

            $request->end

        );





        while(

            $time < $end

        )

        {


            $next = $time +

                ($request->interval * 60);





            $slots[] = AppointmentSlot::create([


                'service_id'=>$request->service_id,


                'date'=>$request->date,


                'start_time'=>date(

                    'H:i',

                    $time

                ),


                'end_time'=>date(

                    'H:i',

                    $next

                ),


                'capacity'=>$request->capacity ?? 1,


                'booked'=>0,


                'status'=>'open'


            ]);





            $time = $next;


        }





        return response()->json([


            'message'=>'สร้างตาราง Slot สำเร็จ',


            'total'=>count($slots)


        ]);


    }


}