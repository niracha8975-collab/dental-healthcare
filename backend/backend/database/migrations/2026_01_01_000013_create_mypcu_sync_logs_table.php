<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('mypcu_sync_logs', function (Blueprint $table) {


            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Sync Information
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'sync_type',
                [
                    'patient',
                    'appointment',
                    'health_record',
                    'dental_record'
                ]
            );



            /*
            |--------------------------------------------------------------------------
            | Reference Data
            |--------------------------------------------------------------------------
            */

            $table->string(
                'reference_type'
            )
            ->nullable();



            $table->unsignedBigInteger(
                'reference_id'
            )
            ->nullable();



            /*
            |--------------------------------------------------------------------------
            | API Data
            |--------------------------------------------------------------------------
            */

            $table->json(
                'request_data'
            )
            ->nullable();



            $table->json(
                'response_data'
            )
            ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Sync Status
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'status',
                [
                    'pending',
                    'success',
                    'failed'
                ]
            )
            ->default('pending');



            $table->text(
                'error_message'
            )
            ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Sync Time
            |--------------------------------------------------------------------------
            */

            $table->timestamp(
                'synced_at'
            )
            ->nullable();



            $table->timestamps();



            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index([
                'sync_type',
                'status'
            ]);


        });

    }



    public function down(): void
    {

        Schema::dropIfExists(
            'mypcu_sync_logs'
        );

    }

};