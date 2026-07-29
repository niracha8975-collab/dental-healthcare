<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

use App\Models\User;

use App\Models\Setting;

use App\Models\DentalService;

use Illuminate\Support\Facades\Hash;



class DatabaseSeeder extends Seeder
{


    public function run(): void
    {


        /*
        |--------------------------------------------------------------------------
        | Create Admin Account
        |--------------------------------------------------------------------------
        */


        $admin = User::create([


            'name' => 'System Administrator',


            'phone' => '0800000000',


            'email' => 'admin@dentalhealthcare.local',


            'password' => Hash::make('Admin@123456'),


            'user_type' => 'admin',


            'status' => 'active'


        ]);





        /*
        |--------------------------------------------------------------------------
        | Create Dental Staff
        |--------------------------------------------------------------------------
        */


        User::create([


            'name' => 'Dental Staff',


            'phone' => '0811111111',


            'email' => 'staff@dentalhealthcare.local',


            'password' => Hash::make('Staff@123456'),


            'user_type' => 'dentist',


            'status' => 'active'


        ]);





        /*
        |--------------------------------------------------------------------------
        | Default Dental Services
        |--------------------------------------------------------------------------
        */


        $services = [


            [

                'code'=>'EXAM',

                'name'=>'ตรวจสุขภาพช่องปาก',

                'category'=>'examination',

                'duration_minutes'=>30,

                'price'=>0

            ],


            [

                'code'=>'SCALING',

                'name'=>'ขูดหินปูน',

                'category'=>'preventive',

                'duration_minutes'=>45,

                'price'=>500

            ],


            [

                'code'=>'FILLING',

                'name'=>'อุดฟัน',

                'category'=>'restorative',

                'duration_minutes'=>60,

                'price'=>500

            ],


            [

                'code'=>'EXTRACTION',

                'name'=>'ถอนฟัน',

                'category'=>'surgery',

                'duration_minutes'=>45,

                'price'=>300

            ],


            [

                'code'=>'FLUORIDE',

                'name'=>'เคลือบฟลูออไรด์',

                'category'=>'preventive',

                'duration_minutes'=>30,

                'price'=>100

            ]


        ];





        foreach($services as $service)
        {


            DentalService::create(

                $service

            );


        }





        /*
        |--------------------------------------------------------------------------
        | Default System Settings
        |--------------------------------------------------------------------------
        */


        $settings = [


            [

                'key'=>'system_name',

                'value'=>'Dental Healthcare',

                'type'=>'string',

                'group'=>'general',

                'is_public'=>true

            ],



            [

                'key'=>'hospital_name',

                'value'=>'โรงพยาบาลส่งเสริมสุขภาพตำบลไร่หลักทอง',

                'type'=>'string',

                'group'=>'general',

                'is_public'=>true

            ],



            [

                'key'=>'primary_color',

                'value'=>'#2E7D32',

                'type'=>'string',

                'group'=>'theme',

                'is_public'=>true

            ],



            [

                'key'=>'booking_enabled',

                'value'=>'true',

                'type'=>'boolean',

                'group'=>'appointment',

                'is_public'=>true

            ],



            [

                'key'=>'mypcu_enabled',

                'value'=>'false',

                'type'=>'boolean',

                'group'=>'integration',

                'is_public'=>false

            ]


        ];





        foreach($settings as $setting)
        {


            Setting::create(

                array_merge(

                    $setting,

                    [

                        'created_by'=>$admin->id

                    ]

                )

            );


        }


    }


}