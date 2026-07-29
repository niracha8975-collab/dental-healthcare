<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;



return new class extends Migration
{


    public function up(): void
    {


        Schema::create('users', function(Blueprint $table){


            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */


            $table->string('name');


            $table->string('phone')

                ->unique();



            $table->string('email')

                ->nullable()

                ->unique();



            /*
            |--------------------------------------------------------------------------
            | Citizen Information
            |--------------------------------------------------------------------------
            */


            $table->string('citizen_id')

                ->nullable()

                ->unique();



            $table->date('birth_date')

                ->nullable();



            $table->enum(

                'gender',

                [

                    'male',

                    'female',

                    'other'

                ]

            )

            ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Authentication
            |--------------------------------------------------------------------------
            */


            $table->string('password');



            $table->string('profile_image')

                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Account Status
            |--------------------------------------------------------------------------
            */


            $table->enum(

                'status',

                [

                    'active',

                    'inactive',

                    'blocked'

                ]

            )

            ->default('active');





            /*
            |--------------------------------------------------------------------------
            | User Type
            |--------------------------------------------------------------------------
            */


            $table->enum(

                'user_type',

                [

                    'citizen',

                    'staff',

                    'dentist',

                    'admin'

                ]

            )

            ->default('citizen');





            /*
            |--------------------------------------------------------------------------
            | Healthcare Integration
            |--------------------------------------------------------------------------
            */


            $table->string('hn')

                ->nullable();



            $table->string('mypcu_id')

                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Device
            |--------------------------------------------------------------------------
            */


            $table->string('device_token')

                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Security
            |--------------------------------------------------------------------------
            */


            $table->timestamp(

                'last_login_at'

            )

            ->nullable();



            $table->rememberToken();



            $table->timestamps();



            $table->softDeletes();



        });


    }





    public function down(): void
    {

        Schema::dropIfExists('users');

    }


};