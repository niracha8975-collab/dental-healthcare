<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\DentalService;

use App\Models\DentalTreatment;

use App\Models\Appointment;

use App\Models\AuditLog;



class ServiceController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Service List
    |--------------------------------------------------------------------------
    */


    public function index(Request $request)

    {


        $services = DentalService::when(

            $request->active,

            function($query){

                $query->where(

                    'active',

                    true

                );

            }

        )

        ->orderBy(

            'sort_order'

        )

        ->get();





        return response()->json([


            'data'=>$services


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Show Service
    |--------------------------------------------------------------------------
    */


    public function show(

        DentalService $service

    )

    {


        return response()->json([


            'data'=>$service


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Create Service
    |--------------------------------------------------------------------------
    */


    public function store(

        Request $request

    )

    {


        $request->validate([


            'name'=>'required',


            'price'=>'required|numeric',


            'duration'=>'required|integer'


        ]);





        $service = DentalService::create([


            'code'=>

                DentalService::generateCode(),


            'name'=>

                $request->name,


            'category'=>

                $request->category,


            'description'=>

                $request->description,


            'price'=>

                $request->price,


            'duration'=>

                $request->duration,


            'active'=>true,


            'sort_order'=>

                $request->sort_order ?? 0



        ]);





        AuditLog::record(

            'create',

            'DentalService',

            $service->id

        );





        return response()->json([


            'message'=>'เพิ่มบริการสำเร็จ',


            'data'=>$service


        ],201);


    }





    /*
    |--------------------------------------------------------------------------
    | Update Service
    |--------------------------------------------------------------------------
    */


    public function update(

        Request $request,

        DentalService $service

    )

    {


        $old = $service->toArray();





        $service->update(

            $request->only([


                'name',

                'category',

                'description',

                'price',

                'duration',

                'active',

                'sort_order'


            ])

        );





        AuditLog::record(

            'update',

            'DentalService',

            $service->id,

            $old,

            $service->toArray()

        );





        return response()->json([


            'message'=>'แก้ไขบริการสำเร็จ',


            'data'=>$service


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Toggle Active
    |--------------------------------------------------------------------------
    */


    public function toggle(

        DentalService $service

    )

    {


        $service->update([


            'active'=>

                !$service->active


        ]);





        return response()->json([


            'message'=>'เปลี่ยนสถานะบริการสำเร็จ',


            'active'=>$service->active


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Service Statistics
    |--------------------------------------------------------------------------
    */


    public function statistics(

        DentalService $service

    )

    {


        return response()->json([


            'total_treatment'=>

                DentalTreatment::where(

                    'service_id',

                    $service->id

                )

                ->count(),



            'total_appointment'=>

                Appointment::where(

                    'service_id',

                    $service->id

                )

                ->count(),



            'revenue'=>

                DentalTreatment::where(

                    'service_id',

                    $service->id

                )

                ->sum(

                    'price'

                )



        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Delete Service
    |--------------------------------------------------------------------------
    */


    public function destroy(

        DentalService $service

    )

    {


        if(

            $service->treatments()->exists()

        )

        {


            return response()->json([


                'message'=>

                    'ไม่สามารถลบได้ เนื่องจากมีประวัติการรักษา'


            ],409);


        }





        $service->delete();





        return response()->json([


            'message'=>'ลบบริการสำเร็จ'


        ]);


    }


}