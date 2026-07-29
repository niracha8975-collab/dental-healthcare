<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('appointment_slots', function (Blueprint $table) {


            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Service Schedule
            |--------------------------------------------------------------------------
            */

            $table->date(
                'service_date'
            );


            $table->time(
                'start_time'
            );


            $table->time(
                'end_time'
            );



            /*
            |--------------------------------------------------------------------------
            | Queue Capacity
            |--------------------------------------------------------------------------
            */

            $table->integer(
                'max_queue'
            )
            ->default(20);



            $table->integer(
                'booked_count'
            )
            ->default(0);



            /*
            |--------------------------------------------------------------------------
            | Service Type
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'slot_type',
                [
                    'dental',
                    'thai_medicine',
                    'general'
                ]
            )
            ->default('dental');



            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'status',
                [
                    'available',
                    'full',
                    'closed'
                ]
            )
            ->default('available');



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
                'service_date',
                'status'
            ]);


        });

    }



    public function down(): void
    {

        Schema::dropIfExists(
            'appointment_slots'
        );

    }

};