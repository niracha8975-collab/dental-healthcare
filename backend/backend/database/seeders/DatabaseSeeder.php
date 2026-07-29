<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;



class DatabaseSeeder extends Seeder
{


    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        /*
        |--------------------------------------------------------------------------
        | System Roles & Permissions
        |--------------------------------------------------------------------------
        */

        $this->call([

            RoleSeeder::class,

        ]);



        /*
        |--------------------------------------------------------------------------
        | Dental Service Catalog
        |--------------------------------------------------------------------------
        */

        $this->call([

            DentalServiceSeeder::class,

        ]);



        /*
        |--------------------------------------------------------------------------
        | System Configuration
        |--------------------------------------------------------------------------
        */

        $this->call([

            SettingSeeder::class,

        ]);



    }

}