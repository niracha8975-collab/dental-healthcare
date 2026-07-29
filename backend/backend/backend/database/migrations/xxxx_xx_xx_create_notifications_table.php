<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;



return new class extends Migration
{


    public function up(): void
    {


        Schema::create('notifications', function(Blueprint $table){


            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Receiver
            |--------------------------------------------------------------------------
            */


            $table->foreignId('user_id')

                ->nullable()

                ->constrained('users')

                ->cascadeOnDelete();



            $table->foreignId('patient_id')

                ->nullable()

                ->constrained('patients')

                ->nullOnDelete();





            /*
            |--------------------------------------------------------------------------
            | Notification Type
            |--------------------------------------------------------------------------
            */


            $table->enum(

                'type',

                [

                    'appointment',

                    'queue',

                    'system',

                    'health',

                    'promotion'

                ]

            )

            ->default('system');





            /*
            |--------------------------------------------------------------------------
            | Message
            |--------------------------------------------------------------------------
            */


            $table->string('title');



            $table->text('message');



            $table->json('data')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */


            $table->boolean('is_read')

                ->default(false);



            $table->timestamp('read_at')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Delivery
            |--------------------------------------------------------------------------
            */


            $table->enum(

                'channel',

                [

                    'app',

                    'sms',

                    'email'

                ]

            )

            ->default('app');



            $table->string('firebase_message_id')

                ->nullable();



            $table->timestamp('sent_at')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Reference
            |--------------------------------------------------------------------------
            */


            $table->string('reference_type')

                ->nullable();



            $table->unsignedBigInteger('reference_id')

                ->nullable();





            $table->timestamps();



            $table->softDeletes();



            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */


            $table->index('user_id');

            $table->index('patient_id');

            $table->index('is_read');

            $table->index('type');



        });


    }





    public function down(): void
    {

        Schema::dropIfExists('notifications');

    }


};