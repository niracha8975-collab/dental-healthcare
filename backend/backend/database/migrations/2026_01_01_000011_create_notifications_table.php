<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('notifications', function (Blueprint $table) {


            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Receiver
            |--------------------------------------------------------------------------
            */

            $table->foreignId(
                'user_id'
            )
            ->nullable()
            ->constrained('users')
            ->cascadeOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Notification Content
            |--------------------------------------------------------------------------
            */

            $table->string(
                'title'
            );


            $table->text(
                'message'
            );



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
                    'healthcare',
                    'promotion'
                ]
            )
            ->default('system');



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
            | Additional Data
            |--------------------------------------------------------------------------
            */

            $table->json(
                'data'
            )
            ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Status Tracking
            |--------------------------------------------------------------------------
            */

            $table->timestamp(
                'read_at'
            )
            ->nullable();



            $table->timestamp(
                'sent_at'
            )
            ->nullable();



            $table->enum(
                'status',
                [
                    'pending',
                    'sent',
                    'failed'
                ]
            )
            ->default('pending');



            $table->timestamps();



            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index([
                'user_id',
                'status'
            ]);


        });

    }



    public function down(): void
    {

        Schema::dropIfExists(
            'notifications'
        );

    }

};