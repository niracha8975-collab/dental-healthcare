<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('patients', function (Blueprint $table) {


            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

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
            | Hospital Number
            |--------------------------------------------------------------------------
            */

            $table->string('hn')
                ->unique();



            /*
            |--------------------------------------------------------------------------
            | Citizen Information
            |--------------------------------------------------------------------------
            */

            $table->string(
                'citizen_id',
                13
            )
            ->nullable()
            ->unique();



            /*
            |--------------------------------------------------------------------------
            | Personal Information
            |--------------------------------------------------------------------------
            */

            $table->string('prefix')
                ->nullable();


            $table->string('first_name');


            $table->string('last_name');



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
            | Health Information
            |--------------------------------------------------------------------------
            */

            $table->string('blood_type')
                ->nullable();



            $table->text('medical_condition')
                ->nullable();



            $table->text('drug_allergy')
                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Contact Information
            |--------------------------------------------------------------------------
            */

            $table->text('address')
                ->nullable();



            $table->string('phone')
                ->nullable();



            $table->string('emergency_contact')
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



            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('citizen_id');

            $table->index('hn');

        });

    }



    public function down(): void
    {

        Schema::dropIfExists(
            'patients'
        );

    }

};