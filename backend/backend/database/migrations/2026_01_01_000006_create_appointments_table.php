<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('appointments', function (Blueprint $table) {


            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Patient Relation
            |--------------------------------------------------------------------------
            */

            $table->foreignId(
                'patient_id'
            )
            ->constrained('patients')
            ->cascadeOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Appointment Slot Relation
            |--------------------------------------------------------------------------
            */

            $table->foreignId(
                'slot_id'
            )
            ->nullable()
            ->constrained('appointment_slots')
            ->nullOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Appointment Information
            |--------------------------------------------------------------------------
            */

            $table->string(
                'appointment_code'
            )
            ->unique();



            $table->enum(
                'service_type',
                [
                    'dental',
                    'thai_medicine',
                    'general'
                ]
            )
            ->default('dental');



            $table->date(
                'appointment_date'
            );



            $table->integer(
                'queue_number'
            )
            ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'status',
                [
                    'pending',
                    'confirmed',
                    'checked_in',
                    'completed',
                    'cancelled',
                    'no_show'
                ]
            )
            ->default('pending');



            /*
            |--------------------------------------------------------------------------
            | Detail
            |--------------------------------------------------------------------------
            */

            $table->text(
                'reason'
            )
            ->nullable();



            $table->text(
                'note'
            )
            ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Staff Creator
            |--------------------------------------------------------------------------
            */

            $table->foreignId(
                'created_by'
            )
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Tracking Time
            |--------------------------------------------------------------------------
            */

            $table->timestamp(
                'confirmed_at'
            )
            ->nullable();



            $table->timestamp(
                'completed_at'
            )
            ->nullable();



            $table->timestamps();



            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index([
                'appointment_date',
                'status'
            ]);


            $table->index(
                'queue_number'
            );


        });

    }



    public function down(): void
    {

        Schema::dropIfExists(
            'appointments'
        );

    }

};