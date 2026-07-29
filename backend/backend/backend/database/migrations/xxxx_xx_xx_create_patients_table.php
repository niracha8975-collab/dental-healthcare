<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;



return new class extends Migration
{


    public function up(): void
    {


        Schema::create('patients', function(Blueprint $table){


            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Link User Account
            |--------------------------------------------------------------------------
            */


            $table->foreignId('user_id')

                ->nullable()

                ->constrained('users')

                ->nullOnDelete();





            /*
            |--------------------------------------------------------------------------
            | Patient Identity
            |--------------------------------------------------------------------------
            */


            $table->string('hn')

                ->unique();



            $table->string('citizen_id')

                ->unique();



            $table->prefix('title')

                ->nullable();



            $table->string('first_name');



            $table->string('last_name');



            $table->date('birth_date');



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
            | Contact Information
            |--------------------------------------------------------------------------
            */


            $table->string('phone')

                ->nullable();



            $table->string('email')

                ->nullable();



            $table->text('address')

                ->nullable();



            $table->string('moo')

                ->nullable();



            $table->string('subdistrict')

                ->nullable();



            $table->string('district')

                ->nullable();



            $table->string('province')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Healthcare Information
            |--------------------------------------------------------------------------
            */


            $table->string('blood_group')

                ->nullable();



            $table->text('medical_history')

                ->nullable();



            $table->text('drug_allergy')

                ->nullable();



            $table->text('congenital_disease')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Dental Public Health Group
            |--------------------------------------------------------------------------
            */


            $table->enum(

                'patient_group',

                [

                    'child',

                    'student',

                    'pregnant',

                    'elderly',

                    'general'

                ]

            )

            ->default('general');





            /*
            |--------------------------------------------------------------------------
            | My PCU Integration
            |--------------------------------------------------------------------------
            */


            $table->string('mypcu_patient_id')

                ->nullable();



            $table->timestamp('last_sync_at')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */


            $table->enum(

                'status',

                [

                    'active',

                    'inactive'

                ]

            )

            ->default('active');



            $table->timestamps();



            $table->softDeletes();



            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */


            $table->index('citizen_id');

            $table->index('hn');

            $table->index('patient_group');



        });


    }





    public function down(): void
    {

        Schema::dropIfExists('patients');

    }


};