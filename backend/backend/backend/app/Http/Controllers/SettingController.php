<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Setting;

use App\Models\AuditLog;

use Illuminate\Support\Facades\Storage;



class SettingController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Get All Settings
    |--------------------------------------------------------------------------
    */


    public function index()
    {


        return response()->json([


            'data'=>Setting::all()


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Get Public Settings
    |--------------------------------------------------------------------------
    */


    public function publicSettings()
    {


        $settings = Setting::whereIn(

            'key',

            [

                'app_name',

                'organization_name',

                'logo',

                'primary_color',

                'booking_enabled',

                'notification_enabled'


            ]

        )->get();





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

        Request $request

    )

    {


        $request->validate([


            'key'=>'required',


            'value'=>'nullable'


        ]);





        $setting = Setting::where(

            'key',

            $request->key

        )->first();





        $old = $setting ?

            $setting->toArray()

            : [];





        if($setting)

        {


            $setting->update([


                'value'=>$request->value


            ]);


        }

        else

        {


            $setting = Setting::create([


                'key'=>$request->key,


                'value'=>$request->value


            ]);


        }





        AuditLog::record(

            'update',

            'Setting',

            $setting->id,

            $old,

            $setting->toArray()

        );





        return response()->json([


            'message'=>'บันทึกการตั้งค่าสำเร็จ',


            'data'=>$setting


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Update Organization Profile
    |--------------------------------------------------------------------------
    */


    public function organization(

        Request $request

    )

    {


        $data = [


            'organization_name'=>

                $request->organization_name,


            'address'=>

                $request->address,


            'phone'=>

                $request->phone,


            'email'=>

                $request->email,


            'director_name'=>

                $request->director_name


        ];





        foreach($data as $key=>$value)

        {


            Setting::updateOrCreate([


                'key'=>$key


            ],[


                'value'=>$value


            ]);


        }





        return response()->json([


            'message'=>'อัปเดตข้อมูลหน่วยงานสำเร็จ'


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





        Setting::updateOrCreate([


            'key'=>'logo'


        ],[


            'value'=>$path


        ]);





        return response()->json([


            'message'=>'อัปโหลด Logo สำเร็จ',


            'path'=>$path


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Booking Configuration
    |--------------------------------------------------------------------------
    */


    public function bookingConfig(

        Request $request

    )

    {


        $configs = [


            'booking_enabled'=>

                $request->booking_enabled,


            'max_booking_per_day'=>

                $request->max_booking_per_day,


            'advance_booking_days'=>

                $request->advance_booking_days,


            'cancel_before_hours'=>

                $request->cancel_before_hours


        ];





        foreach($configs as $key=>$value)

        {


            Setting::updateOrCreate([


                'key'=>$key


            ],[


                'value'=>$value


            ]);


        }





        return response()->json([


            'message'=>'ตั้งค่าการจองคิวสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Notification Configuration
    |--------------------------------------------------------------------------
    */


    public function notificationConfig(

        Request $request

    )

    {


        Setting::updateOrCreate([


            'key'=>'notification_enabled'


        ],[


            'value'=>$request->enabled


        ]);





        Setting::updateOrCreate([


            'key'=>'reminder_before_days'


        ],[


            'value'=>$request->reminder_before_days


        ]);





        return response()->json([


            'message'=>'ตั้งค่าการแจ้งเตือนสำเร็จ'


        ]);


    }


}