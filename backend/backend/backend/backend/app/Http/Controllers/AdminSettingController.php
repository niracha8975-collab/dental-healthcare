<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use App\Models\SystemSetting;

use App\Models\ServiceCategory;

use App\Models\DentalService;

use App\Models\User;

use App\Models\AuditLog;



class AdminSettingController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Get All Settings
    |--------------------------------------------------------------------------
    */


    public function index()

    {


        return response()->json([


            'settings'=>

                SystemSetting::all(),



            'services'=>

                DentalService::with(

                    'category'

                )->get(),



            'categories'=>

                ServiceCategory::all()



        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Update General Setting
    |--------------------------------------------------------------------------
    */


    public function update(

        Request $request

    )

    {


        foreach(

            $request->settings as $key=>$value

        )

        {


            SystemSetting::updateOrCreate(

                [

                    'key'=>$key

                ],

                [

                    'value'=>$value

                ]

            );


        }





        AuditLog::record(

            'update_setting',

            'SystemSetting',

            null

        );





        return response()->json([


            'message'=>'บันทึกค่าระบบสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Organization Setting
    |--------------------------------------------------------------------------
    */


    public function organization(

        Request $request

    )

    {


        $data = [


            'organization_name'=>

                $request->organization_name,


            'hospital_code'=>

                $request->hospital_code,


            'address'=>

                $request->address,


            'phone'=>

                $request->phone


        ];





        foreach($data as $key=>$value)

        {


            SystemSetting::updateOrCreate(

                [

                    'key'=>$key

                ],

                [

                    'value'=>$value

                ]

            );


        }





        return response()->json([


            'message'=>'แก้ไขข้อมูลหน่วยงานสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Application Branding
    |--------------------------------------------------------------------------
    */


    public function branding(

        Request $request

    )

    {


        $request->validate([


            'app_name'=>'required'


        ]);





        SystemSetting::updateOrCreate(

            [

                'key'=>'app_name'

            ],

            [

                'value'=>$request->app_name

            ]

        );





        SystemSetting::updateOrCreate(

            [

                'key'=>'app_title'

            ],

            [

                'value'=>$request->app_title

            ]

        );





        return response()->json([


            'message'=>'เปลี่ยนชื่อระบบสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Upload Logo
    |--------------------------------------------------------------------------
    */


    public function logo(

        Request $request

    )

    {


        $request->validate([


            'logo'=>'required|image|max:2048'


        ]);





        $path = $request

            ->file('logo')

            ->store(

                'branding',

                'public'

            );





        SystemSetting::updateOrCreate(

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
    | Theme Setting
    |--------------------------------------------------------------------------
    */


    public function theme(

        Request $request

    )

    {


        SystemSetting::updateOrCreate(

            [

                'key'=>'theme'

            ],

            [

                'value'=>json_encode([


                    'primary'=>

                        $request->primary,


                    'secondary'=>

                        $request->secondary,


                    'mode'=>

                        $request->mode ?? 'light'


                ])

            ]

        );





        return response()->json([


            'message'=>'ตั้งค่า Theme สำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Dental Service Management
    |--------------------------------------------------------------------------
    */


    public function service(

        Request $request

    )

    {


        $service = DentalService::updateOrCreate(

            [

                'id'=>

                    $request->id

            ],

            [

                'category_id'=>

                    $request->category_id,


                'name'=>

                    $request->name,


                'duration'=>

                    $request->duration,


                'active'=>

                    true

            ]

        );





        return response()->json([


            'message'=>'บันทึกบริการสำเร็จ',


            'data'=>$service


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Appointment Configuration
    |--------------------------------------------------------------------------
    */


    public function appointmentConfig(

        Request $request

    )

    {


        SystemSetting::updateOrCreate(

            [

                'key'=>'appointment_config'

            ],

            [

                'value'=>json_encode([


                    'advance_day'=>

                        $request->advance_day,


                    'slot_minutes'=>

                        $request->slot_minutes,


                    'allow_walkin'=>

                        $request->allow_walkin


                ])

            ]

        );





        return response()->json([


            'message'=>'ตั้งค่าการจองสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | My PCU Configuration
    |--------------------------------------------------------------------------
    */


    public function myPCU(

        Request $request

    )

    {


        SystemSetting::updateOrCreate(

            [

                'key'=>'mypcu_config'

            ],

            [

                'value'=>json_encode([


                    'url'=>

                        $request->url,


                    'token'=>

                        encrypt(

                            $request->token

                        )

                ])

            ]

        );





        return response()->json([


            'message'=>'ตั้งค่า My PCU สำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Admin User Management
    |--------------------------------------------------------------------------
    */


    public function adminUsers()

    {


        return response()->json([


            'data'=>

                User::whereHas(

                    'roles',

                    function($q){


                        $q->where(

                            'name',

                            'admin'

                        );


                    }

                )->get()


        ]);


    }


}