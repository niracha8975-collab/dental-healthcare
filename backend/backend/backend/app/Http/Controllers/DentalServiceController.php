<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\DentalService;

use App\Models\AuditLog;

use App\Models\DentalTreatment;



class DentalServiceController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Public Service List
    |--------------------------------------------------------------------------
    */


    public function index()
    {


        $services = DentalService::active()

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
    | Service Detail
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


            'category'=>'required',


            'price'=>'required|numeric'


        ]);





        $service = DentalService::create([


            'code'=>$request->code ?? 

                DentalService::generateCode(),


            'name'=>$request->name,


            'category'=>$request->category,


            'description'=>$request->description,


            'price'=>$request->price,


            'duration'=>$request->duration ?? 30,


            'status'=>'active',


            'sort_order'=>$request->sort_order ?? 0


        ]);





        AuditLog::record(

            'create',

            'DentalService',

            $service->id,

            [],

            $service->toArray()

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
    | Toggle Status
    |--------------------------------------------------------------------------
    */


    public function toggle(

        DentalService $service

    )

    {


        $service->update([


            'status'=>

                $service->status === 'active'

                ? 'inactive'

                : 'active'


        ]);





        return response()->json([


            'message'=>'เปลี่ยนสถานะบริการสำเร็จ',


            'status'=>$service->status


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


        $service->delete();





        AuditLog::record(

            'delete',

            'DentalService',

            $service->id

        );





        return response()->json([


            'message'=>'ลบบริการสำเร็จ'


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


        $total = DentalTreatment::where(

            'service_id',

            $service->id

        )

        ->count();





        $income = DentalTreatment::where(

            'service_id',

            $service->id

        )

        ->sum(

            'price'

        );





        return response()->json([


            'service'=>$service->name,


            'total'=>$total,


            'income'=>$income


        ]);


    }


}