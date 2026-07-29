<?php

namespace App\Http\Controllers;


use App\Models\DentalService;

use App\Models\AuditLog;

use Illuminate\Http\Request;



class DentalServiceController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | List Services
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {


        $services = DentalService::query();



        if($request->category)
        {

            $services->where(

                'category',

                $request->category

            );

        }



        if($request->status)
        {

            $services->where(

                'status',

                $request->status

            );

        }



        return response()->json([


            'success'=>true,


            'data'=>

                $services

                ->latest()

                ->paginate(20)


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Active Services For Citizen
    |--------------------------------------------------------------------------
    */

    public function active()
    {


        return response()->json([


            'success'=>true,


            'data'=>

                DentalService::active()

                ->get()


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Create Service
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {


        $validated = $request->validate([


            'code'=>

                'required|string|unique:dental_services',


            'name'=>

                'required|string',


            'category'=>

                'required|string',


            'duration_minutes'=>

                'required|integer',


            'price'=>

                'nullable|numeric',


            'description'=>

                'nullable|string'


        ]);



        $service = DentalService::create([


            ...$validated,


            'status'=>'active',


            'created_by'=>

                auth()->id()


        ]);



        AuditLog::createLog(

            'CREATE',

            'DENTAL_SERVICE',

            'DentalService',

            $service->id

        );



        return response()->json([


            'success'=>true,


            'message'=>

                'เพิ่มบริการทันตกรรมสำเร็จ',


            'data'=>$service


        ],201);

    }





    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(

        DentalService $dentalService

    )
    {


        return response()->json([


            'success'=>true,


            'data'=>$dentalService


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(

        Request $request,

        DentalService $dentalService

    )
    {


        $old = $dentalService->toArray();



        $dentalService->update(

            $request->all()

        );



        AuditLog::createLog(

            'UPDATE',

            'DENTAL_SERVICE',

            'DentalService',

            $dentalService->id,

            $old,

            $dentalService->fresh()->toArray()

        );



        return response()->json([


            'success'=>true,


            'message'=>

                'แก้ไขบริการสำเร็จ',


            'data'=>$dentalService


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Toggle Status
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(

        DentalService $dentalService

    )
    {


        $dentalService->update([


            'status'=>

                $dentalService->status === 'active'

                    ? 'inactive'

                    : 'active'


        ]);



        return response()->json([


            'success'=>true,


            'message'=>

                'เปลี่ยนสถานะบริการแล้ว',


            'data'=>$dentalService


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(

        DentalService $dentalService

    )
    {


        AuditLog::createLog(

            'DELETE',

            'DENTAL_SERVICE',

            'DentalService',

            $dentalService->id

        );



        $dentalService->delete();



        return response()->json([


            'success'=>true,


            'message'=>

                'ลบบริการสำเร็จ'


        ]);

    }



}