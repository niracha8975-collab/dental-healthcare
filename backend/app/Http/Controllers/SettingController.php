<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use App\Models\Setting;

use App\Models\AuditLog;



class SettingController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Get All Settings
    |--------------------------------------------------------------------------
    */


    public function index()

    {


        $settings = Setting::all();


        return response()->json([

            'data'=>$settings

        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Get Public Settings
    |--------------------------------------------------------------------------
    */


    public function public()

    {


        $settings = Setting::where(

            'is_public',

            true

        )

        ->get()

        ->pluck(

            'value',

            'key'

        );





        return response()->json([


            'data'=>$settings


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Update Setting
    |--------------------------------------------------------------------------
    */


    public function update(

        Request $request,

        $key

    )

    {


        $request->validate([


            'value'=>'required'


        ]);





        $setting = Setting::updateOrCreate(

            [

                'key'=>$key

            ],

            [

                'value'=>$request->value,

                'updated_by'=>auth()->id()

            ]

        );





        AuditLog::record(

            'update',

            'Setting',

            $setting->id

        );





        return response()->json([


            'message'=>'แก้ไขค่าระบบสำเร็จ',


            'data'=>$setting


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Update Multiple Settings
    |--------------------------------------------------------------------------
    */


    public function bulkUpdate(

        Request $request

    )

    {


        foreach(

            $request->settings as $key=>$value

        )

        {


            Setting::updateOrCreate(

                [

                    'key'=>$key

                ],

                [

                    'value'=>$value,

                    'updated_by'=>auth()->id()

                ]

            );


        }





        return response()->json([


            'message'=>'บันทึกค่าระบบทั้งหมดสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Upload Logo
    |--------------------------------------------------------------------------
    */


    public function uploadLogo(

        Request $request

    )

    {


        $request->validate([


            'logo'=>'required|image|max:2048'


        ]);





        $path = $request

            ->file('logo')

            ->store(

                'settings',

                'public'

            );





        Setting::updateOrCreate(

            [

                'key'=>'logo'

            ],

            [

                'value'=>$path

            ]

        );





        return response()->json([


            'message'=>'อัปโหลด Logo สำเร็จ',


            'path'=>$path


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Queue Configuration
    |--------------------------------------------------------------------------
    */


    public function queueSetting()

    {


        return response()->json([


            'queue_prefix'=>

                Setting::getValue(

                    'queue_prefix',

                    'A'

                ),



            'start_number'=>

                Setting::getValue(

                    'queue_start',

                    1

                ),



            'daily_reset'=>

                Setting::getValue(

                    'queue_reset',

                    true

                )



        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Reset Default
    |--------------------------------------------------------------------------
    */


    public function reset()

    {


        $defaults = [


            'system_name'=>

                'Dental Healthcare',


            'organization'=>

                'โรงพยาบาลส่งเสริมสุขภาพตำบล',


            'theme'=>

                'green',


            'queue_prefix'=>

                'A'


        ];





        foreach($defaults as $key=>$value)

        {


            Setting::updateOrCreate(

                [

                    'key'=>$key

                ],

                [

                    'value'=>$value

                ]

            );


        }





        return response()->json([


            'message'=>'คืนค่าระบบเริ่มต้นสำเร็จ'


        ]);


    }


}