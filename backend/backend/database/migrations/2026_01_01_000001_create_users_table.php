<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('users', function (Blueprint $table) {


            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            $table->string('name');

            $table->string('email')
                ->nullable()
                ->unique();


            $table->string('phone')
                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Citizen Information
            |--------------------------------------------------------------------------
            |
            | สำหรับประชาชนไทย
            |
            */

            $table->string('citizen_id', 13)
                ->nullable()
                ->unique();



            /*
            |--------------------------------------------------------------------------
            | Authentication
            |--------------------------------------------------------------------------
            */

            $table->string('password')
                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | User Type
            |--------------------------------------------------------------------------
            |
            | admin
            | staff
            | dentist
            | citizen
            |
            */

            $table->enum(
                'user_type',
                [
                    'admin',
                    'staff',
                    'dentist',
                    'citizen'
                ]
            )
            ->default('citizen');



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
            | Security Tracking
            |--------------------------------------------------------------------------
            */

            $table->timestamp(
                'last_login_at'
            )
            ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Remember Token
            |--------------------------------------------------------------------------
            */

            $table->rememberToken();



            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();



            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index(
                'user_type'
            );


            $table->index(
                'status'
            );


        });



        /*
        |--------------------------------------------------------------------------
        | Password Reset Tokens
        |--------------------------------------------------------------------------
        */

        Schema::create(
            'password_reset_tokens',
            function (Blueprint $table) {

                $table->string('email')
                    ->primary();


                $table->string('token');


                $table->timestamp(
                    'created_at'
                )
                ->nullable();

            }
        );



        /*
        |--------------------------------------------------------------------------
        | User Sessions
        |--------------------------------------------------------------------------
        */

        Schema::create(
            'sessions',
            function (Blueprint $table) {


                $table->string('id')
                    ->primary();


                $table->foreignId('user_id')
                    ->nullable()
                    ->index();


                $table->string(
                    'ip_address',
                    45
                )
                ->nullable();


                $table->text(
                    'user_agent'
                )
                ->nullable();


                $table->text(
                    'payload'
                );


                $table->integer(
                    'last_activity'
                )
                ->index();


            }
        );

    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists(
            'sessions'
        );


        Schema::dropIfExists(
            'password_reset_tokens'
        );


        Schema::dropIfExists(
            'users'
        );

    }

};