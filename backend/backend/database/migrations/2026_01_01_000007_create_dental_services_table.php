<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('dental_services', function (Blueprint $table) {


            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Service Information
            |--------------------------------------------------------------------------
            */

            $table->string('code')
                ->unique();


            $table->string('name');


            $table->text('description')
                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Service Category
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'category',
                [
                    'examination',
                    'preventive',
                    'restorative',
                    'surgery',
                    'periodontal',
                    'other'
                ]
            )
            ->default('examination');



            /*
            |--------------------------------------------------------------------------
            | Service Duration
            |--------------------------------------------------------------------------
            */

            $table->integer('duration_minutes')
                ->default(30);



            /*
            |--------------------------------------------------------------------------
            | Cost
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'price',
                10,
                2
            )
            ->default(0);



            /*
            |--------------------------------------------------------------------------
            | Appointment Requirement
            |--------------------------------------------------------------------------
            */

            $table->boolean(
                'requires_appointment'
            )
            ->default(true);



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



            /*
            |--------------------------------------------------------------------------
            | Creator
            |--------------------------------------------------------------------------
            */

            $table->foreignId(
                'created_by'
            )
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();



            $table->timestamps();



            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index([
                'category',
                'status'
            ]);


        });

    }



    public function down(): void
    {

        Schema::dropIfExists(
            'dental_services'
        );

    }

};