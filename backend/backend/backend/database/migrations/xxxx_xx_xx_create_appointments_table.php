<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;



return new class extends Migration
{


    public function up(): void
    {


        Schema::create('appointments', function(Blueprint $table){


            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Patient
            |--------------------------------------------------------------------------
            */


            $table->foreignId('patient_id')

                ->constrained('patients')

                ->cascadeOnDelete();





            /*
            |--------------------------------------------------------------------------
            | Service Information
            |--------------------------------------------------------------------------
            */


            $table->foreignId('service_id')

                ->constrained('dental_services')

                ->cascadeOnDelete();



            $table->foreignId('slot_id')

                ->nullable()

                ->constrained('appointment_slots')

                ->nullOnDelete();





            /*
            |--------------------------------------------------------------------------
            | Appointment Number
            |--------------------------------------------------------------------------
            */


            $table->string('appointment_no')

                ->unique();



            $table->integer('queue_number')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Appointment Date Time
            |--------------------------------------------------------------------------
            */


            $table->date('appointment_date');



            $table->time('appointment_time');





            /*
            |--------------------------------------------------------------------------
            | Booking Source
            |--------------------------------------------------------------------------
            */


            $table->enum(

                'booking_source',

                [

                    'mobile_app',

                    'staff',

                    'walk_in'

                ]

            )

            ->default('mobile_app');





            /*
            |--------------------------------------------------------------------------
            | Appointment Status
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

                    'missed'

                ]

            )

            ->default('pending');





            /*
            |--------------------------------------------------------------------------
            | Reason / Note
            |--------------------------------------------------------------------------
            */


            $table->text('symptom')

                ->nullable();



            $table->text('note')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Staff Processing
            |--------------------------------------------------------------------------
            */


            $table->foreignId('confirmed_by')

                ->nullable()

                ->constrained('users')

                ->nullOnDelete();



            $table->timestamp('confirmed_at')

                ->nullable();



            $table->timestamp('check_in_at')

                ->nullable();



            $table->timestamp('completed_at')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Cancellation
            |--------------------------------------------------------------------------
            */


            $table->text('cancel_reason')

                ->nullable();



            $table->timestamp('cancelled_at')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Integration
            |--------------------------------------------------------------------------
            */


            $table->string('mypcu_appointment_id')

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


            $table->index(

                [

                    'appointment_date',

                    'status'

                ]

            );



            $table->index('patient_id');

            $table->index('appointment_no');



        });


    }





    public function down(): void
    {

        Schema::dropIfExists('appointments');

    }


};