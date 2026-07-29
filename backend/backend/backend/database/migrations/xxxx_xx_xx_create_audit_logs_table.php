<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;



return new class extends Migration
{


    public function up(): void
    {


        Schema::create('audit_logs', function(Blueprint $table){


            $table->id();



            /*
            |--------------------------------------------------------------------------
            | User Information
            |--------------------------------------------------------------------------
            */


            $table->foreignId('user_id')

                ->nullable()

                ->constrained('users')

                ->nullOnDelete();





            /*
            |--------------------------------------------------------------------------
            | Action Information
            |--------------------------------------------------------------------------
            */


            $table->string('action');



            $table->string('module');



            $table->string('description')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Reference Data
            |--------------------------------------------------------------------------
            */


            $table->string('model_type')

                ->nullable();



            $table->unsignedBigInteger('model_id')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Data Change Tracking
            |--------------------------------------------------------------------------
            */


            $table->json('old_values')

                ->nullable();



            $table->json('new_values')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Security Information
            |--------------------------------------------------------------------------
            */


            $table->string('ip_address')

                ->nullable();



            $table->text('user_agent')

                ->nullable();



            $table->string('device_name')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Timestamp
            |--------------------------------------------------------------------------
            */


            $table->timestamp('created_at')

                ->useCurrent();



            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */


            $table->index('user_id');

            $table->index('action');

            $table->index('module');

            $table->index('model_id');

            $table->index('created_at');



        });


    }





    public function down(): void
    {

        Schema::dropIfExists('audit_logs');

    }


};