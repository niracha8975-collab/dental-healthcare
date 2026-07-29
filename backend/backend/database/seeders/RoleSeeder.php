<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class RoleSeeder extends Seeder
{


    /**
     * Create default roles
     */
    public function run(): void
    {


        /*
        |--------------------------------------------------------------------------
        | Create Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [


            /*
            | User Management
            */

            'manage users',
            'view users',
            'create users',
            'update users',
            'delete users',



            /*
            | Appointment
            */

            'view appointments',
            'create appointments',
            'update appointments',
            'cancel appointments',



            /*
            | Patient
            */

            'view patients',
            'create patients',
            'update patients',
            'view medical records',



            /*
            | Dental
            */

            'view dental records',
            'create dental records',
            'update dental records',
            'manage dental treatments',
            'manage odontograms',



            /*
            | Reports
            */

            'view reports',
            'export reports',



            /*
            | Settings
            */

            'manage settings',



            /*
            | MyPCU
            */

            'manage mypcu sync'


        ];



        foreach ($permissions as $permission) {


            Permission::create([

                'name' => $permission,

                'guard_name' => 'web'

            ]);

        }




        /*
        |--------------------------------------------------------------------------
        | Create Roles
        |--------------------------------------------------------------------------
        */


        $superAdmin = Role::create([

            'name'=>'Super Admin',

            'guard_name'=>'web'

        ]);



        $admin = Role::create([

            'name'=>'Admin',

            'guard_name'=>'web'

        ]);



        $dentist = Role::create([

            'name'=>'Dentist',

            'guard_name'=>'web'

        ]);



        $staff = Role::create([

            'name'=>'Staff',

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


        $superAdmin
            ->givePermissionTo(
                Permission::all()
            );



        $admin
            ->givePermissionTo([

                'manage users',

                'view appointments',

                'create appointments',

                'update appointments',

                'view patients',

                'view reports',

                'manage settings'

            ]);




        $dentist
            ->givePermissionTo([

                'view patients',

                'view dental records',

                'create dental records',

                'update dental records',

                'manage dental treatments',

                'manage odontograms'

            ]);




        $staff
            ->givePermissionTo([

                'view appointments',

                'create appointments',

                'update appointments',

                'view patients'

            ]);




        $citizen
            ->givePermissionTo([

                'create appointments',

                'view appointments'

            ]);



    }

}