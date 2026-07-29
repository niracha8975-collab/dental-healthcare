<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

use App\Models\User;

use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;



class AdminUserSeeder extends Seeder
{


    public function run(): void
    {


        /*
        |--------------------------------------------------------------------------
        | Create Main Administrator
        |--------------------------------------------------------------------------
        */


        $admin = User::updateOrCreate(


            [

                'email'=>'admin@dentalhealthcare.local'

            ],


            [

                'name'=>'ผู้ดูแลระบบ Dental Healthcare',


                'phone'=>'0800000000',


                'password'=>Hash::make(

                    'Admin@123456'

                ),


                'user_type'=>'admin',


                'status'=>'active'


            ]


        );





        /*
        |--------------------------------------------------------------------------
        | Assign Admin Role
        |--------------------------------------------------------------------------
        */


        $adminRole = Role::where(

            'name',

            'Admin'

        )->first();





        if($adminRole)
        {


            $admin->assignRole(

                $adminRole

            );


        }





        /*
        |--------------------------------------------------------------------------
        | Create Dental Officer Account
        |--------------------------------------------------------------------------
        */


        $dentist = User::updateOrCreate(


            [

                'email'=>'dentist@dentalhealthcare.local'

            ],


            [

                'name'=>'ทันตบุคลากร',


                'phone'=>'0811111111',


                'password'=>Hash::make(

                    'Dentist@123456'

                ),


                'user_type'=>'dentist',


                'status'=>'active'


            ]


        );





        $dentistRole = Role::where(

            'name',

            'Dentist'

        )->first();





        if($dentistRole)
        {


            $dentist->assignRole(

                $dentistRole

            );


        }





        /*
        |--------------------------------------------------------------------------
        | Create Staff Account
        |--------------------------------------------------------------------------
        */


        $staff = User::updateOrCreate(


            [

                'email'=>'staff@dentalhealthcare.local'

            ],


            [

                'name'=>'เจ้าหน้าที่ทันตกรรม',


                'phone'=>'0822222222',


                'password'=>Hash::make(

                    'Staff@123456'

                ),


                'user_type'=>'staff',


                'status'=>'active'


            ]


        );





        $staffRole = Role::where(

            'name',

            'Dental Staff'

        )->first();





        if($staffRole)
        {


            $staff->assignRole(

                $staffRole

            );


        }


    }


}