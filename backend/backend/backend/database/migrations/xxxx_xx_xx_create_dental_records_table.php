<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;



return new class extends Migration
{


    public function up(): void
    {


        Schema::create('dental_records', function(Blueprint $table){


            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Patient & Appointment Link
            |--------------------------------------------------------------------------
            */


            $table->foreignId('patient_id')

                ->constrained('patients')

                ->cascadeOnDelete();



            $table->foreignId('appointment_id')

                ->nullable()

                ->constrained('appointments')

                ->nullOnDelete();





            /*
            |--------------------------------------------------------------------------
            | Dentist / Provider
            |--------------------------------------------------------------------------
            */


            $table->foreignId('dentist_id')

                ->nullable()

                ->constrained('users')

                ->nullOnDelete();





            /*
            |--------------------------------------------------------------------------
            | Examination Information
            |--------------------------------------------------------------------------
            */


            $table->date('examination_date');



            $table->text('chief_complaint')

                ->nullable();



            $table->text('history_present_illness')

                ->nullable();



            $table->text('medical_history')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Dental Diagnosis
            |--------------------------------------------------------------------------
            */


            $table->text('diagnosis')

                ->nullable();



            $table->text('treatment_plan')

                ->nullable();



            $table->text('clinical_note')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Oral Health Assessment
            |--------------------------------------------------------------------------
            */


            $table->integer('dmft_score')

                ->nullable();



            $table->integer('dmfs_score')

                ->nullable();



            $table->integer('decayed_teeth')

                ->default(0);



            $table->integer('missing_teeth')

                ->default(0);



            $table->integer('filled_teeth')

                ->default(0);





            /*
            |--------------------------------------------------------------------------
            | Treatment Status
            |--------------------------------------------------------------------------
            */


            $table->enum(

                'status',

                [

                    'in_progress',

                    'completed',

                    'follow_up'

                ]

            )

            ->default('in_progress');





            /*
            |--------------------------------------------------------------------------
            | Follow Up
            |--------------------------------------------------------------------------
            */


            $table->date('next_visit_date')

                ->nullable();



            $table->text('follow_up_note')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Integration
            |--------------------------------------------------------------------------
            */


            $table->string('mypcu_record_id')

                ->nullable();



            $table->timestamp('last_sync_at')

                ->nullable();





            $table->timestamps();



            $table->softDeletes();



            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */


            $table->index('patient_id');

            $table->index('examination_date');

            $table->index('status');



        });


    }





    public function down(): void
    {

        Schema::dropIfExists('dental_records');

    }


};