<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;



return new class extends Migration
{


    public function up(): void
    {


        Schema::create('appointment_slots', function(Blueprint $table){


            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Dental Service
            |--------------------------------------------------------------------------
            */


            $table->foreignId('service_id')

                ->constrained('dental_services')

                ->cascadeOnDelete();





            /*
            |--------------------------------------------------------------------------
            | Service Date & Time
            |--------------------------------------------------------------------------
            */


            $table->date('service_date');



            $table->time('start_time');



            $table->time('end_time');





            /*
            |--------------------------------------------------------------------------
            | Queue Management
            |--------------------------------------------------------------------------
            */


            $table->integer('max_queue')

                ->default(20);



            $table->integer('booked_count')

                ->default(0);





            /*
            |--------------------------------------------------------------------------
            | Slot Status
            |--------------------------------------------------------------------------
            */


            $table->enum(

                'status',

                [

                    'active',

                    'inactive',

                    'full',

                    'closed'

                ]

            )

            ->default('active');





            /*
            |--------------------------------------------------------------------------
            | Admin Control
            |--------------------------------------------------------------------------
            */


            $table->foreignId('created_by')

                ->nullable()

                ->constrained('users')

                ->nullOnDelete();



            $table->text('note')

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

                    'service_date',

                    'status'

                ]

            );



            $table->index('service_id');



        });


    }





    public function down(): void
    {

        Schema::dropIfExists('appointment_slots');

    }


};