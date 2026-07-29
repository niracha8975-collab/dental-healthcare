<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('dental_records', function (Blueprint $table) {


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

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Appointment Relation
            |--------------------------------------------------------------------------
            */

            $table->foreignId('appointment_id')
                ->nullable()
                ->constrained('appointments')
                ->nullOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Dentist Relation
            |--------------------------------------------------------------------------
            */

            $table->foreignId('dentist_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Visit Information
            |--------------------------------------------------------------------------
            */

            $table->date('visit_date');



            $table->text('chief_complaint')
                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Clinical Information
            |--------------------------------------------------------------------------
            */

            $table->text('diagnosis')
                ->nullable();



            $table->text('oral_condition')
                ->nullable();



            $table->text('treatment_summary')
                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Follow Up
            |--------------------------------------------------------------------------
            */

            $table->date('follow_up_date')
                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Record Status
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'status',
                [
                    'draft',
                    'completed',
                    'follow_up'
                ]
            )
            ->default('draft');



            $table->timestamps();



            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index([
                'patient_id',
                'visit_date'
            ]);


        });

    }



    public function down(): void
    {

        Schema::dropIfExists(
            'dental_records'
        );

    }

};