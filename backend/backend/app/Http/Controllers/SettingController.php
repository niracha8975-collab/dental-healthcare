<?php

namespace App\Http\Controllers;


use App\Models\Setting;

use App\Models\AuditLog;

use Illuminate\Http\Request;



class SettingController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Get Public Settings
    |--------------------------------------------------------------------------
    */

    public function public()
    {


        return response()->json([


            'success'=>true,


            'data'=>

                Setting::public()

                ->get()

                ->pluck(

                    'value',

                    'key'

                )


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | All Settings For Admin
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {


        $settings = Setting::query();



        if($request->group)
        {

            $settings->where(

                'group',

                $request->group

            );

        }



        return response()->json([


            'success'=>true,


            'data'=>$settings

            ->orderBy(

                'group'

            )

            ->get()


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Update Setting
    |--------------------------------------------------------------------------
    */

    public function update(

        Request $request,

        Setting $setting

    )
    {


        $old = $setting->toArray();



        $validated = $request->validate([


            'value'=>

                'required',


            'type'=>

                'nullable|string',


            'description'=>

                'nullable|string'


        ]);



        $setting->update(

            $validated

        );



        AuditLog::createLog(

            'UPDATE',

            'SYSTEM_SETTING',

            'Setting',

            $setting->id,

            $old,

            $setting->fresh()->toArray()

        );



        return response()->json([


            'success'=>true,


            'message'=>

                'บันทึกค่าตั้งค่าสำเร็จ',


            'data'=>$setting


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Create Setting
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {


        $validated = $request->validate([


            'key'=>

                'required|string|unique:settings',


            'value'=>

                'required',


            'type'=>

                'required|string',


            'group'=>

                'required|string',


            'description'=>

                'nullable|string',


            'is_public'=>

                'boolean'


        ]);



        $setting = Setting::create(

            $validated

        );



        AuditLog::createLog(

            'CREATE',

            'SYSTEM_SETTING',

            'Setting',

            $setting->id

        );



        return response()->json([


            'success'=>true,


            'message'=>

                'สร้างค่าตั้งค่าสำเร็จ',


            'data'=>$setting


        ],201);

    }





    /*
    |--------------------------------------------------------------------------
    | Delete Setting
    |--------------------------------------------------------------------------
    */

    public function destroy(

        Setting $setting

    )
    {


        AuditLog::createLog(

            'DELETE',

            'SYSTEM_SETTING',

            'Setting',

            $setting->id

        );



        $setting->delete();



        return response()->json([


            'success'=>true,


            'message'=>

                'ลบค่าตั้งค่าสำเร็จ'


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Bulk Update
    |--------------------------------------------------------------------------
    */

    public function bulkUpdate(

        Request $request

    )
    {


        $request->validate([


            'settings'=>

                'required|array'


        ]);



        foreach(

            $request->settings as $item

        )
        {


            Setting::updateOrCreate(

                [

                    'key'=>

                        $item['key']

                ],

                [

                    'value'=>

                        $item['value'],

                    'type'=>

                        $item['type'] ?? 'string'


                ]

            );


        }



        return response()->json([


            'success'=>true,


            'message'=>

                'อัปเดตค่าตั้งค่าหลายรายการสำเร็จ'


        ]);

    }



}