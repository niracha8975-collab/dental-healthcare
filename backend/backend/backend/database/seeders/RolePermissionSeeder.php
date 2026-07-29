<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;

use Spatie\Permission\Models\Permission;



class RolePermissionSeeder extends Seeder
{


    public function run(): void
    {


        /*
        |--------------------------------------------------------------------------
        | Create Permissions
        |--------------------------------------------------------------------------
        */


        $permissions = [


            /*
            User
            */

            'view_users',

            'create_users',

            'edit_users',

            'delete_users',



            /*
            Patient
            */

            'view_patients',

            'create_patients',

            'edit_patients',

            'delete_patients',



            /*
            Appointment
            */

            'view_appointments',

            'create_appointments',

            'approve_appointments',

            'cancel_appointments',



            /*
            Dental Record
            */

            'view_dental_records',

            'create_dental_records',

            'edit_dental_records',

            'delete_dental_records',



            /*
            Treatment
            */

            'view_treatments',

            'create_treatments',

            'edit_treatments',



            /*
            Reports
            */

            'view_reports',



            /*
            Settings
            */

            'manage_settings',



            /*
            Audit
            */

            'view_audit_logs'


        ];





        foreach($permissions as $permission)
        {


            Permission::create([


                'name'=>$permission,


                'guard_name'=>'web'


            ]);


        }





        /*
        |--------------------------------------------------------------------------
        | Create Roles
        |--------------------------------------------------------------------------
        */


        $admin = Role::create([


            'name'=>'Admin',


            'guard_name'=>'web'


        ]);





        $dentist = Role::create([


            'name'=>'Dentist',


            'guard_name'=>'web'


        ]);





        $staff = Role::create([


            'name'=>'Dental Staff',


            'guard_name'=>'web'


        ]);





        $citizen = Role::create([


            'name'=>'Citizen',


            'guard_name'=>'web'


        ]);





        /*
        |--------------------------------------------------------------------------
        | Assign Permissions
        |--------------------------------------------------------------------------
        */


        $admin->givePermissionTo(

            Permission::all()

        );





        $dentist->givePermissionTo([


            'view_patients',


            'edit_patients',


            'view_appointments',


            'approve_appointments',


            'view_dental_records',


            'create_dental_records',


            'edit_dental_records',


            'view_treatments',


            'create_treatments',


            'edit_treatments',


            'view_reports'


        ]);





        $staff->givePermissionTo([


            'view_patients',


            'create_patients',


            'edit_patients',


            'view_appointments',


            'create_appointments',


            'approve_appointments',


            'cancel_appointments',


            'view_reports'


        ]);





        $citizen->givePermissionTo([


            'view_appointments',


            'create_appointments'


        ]);



    }


}