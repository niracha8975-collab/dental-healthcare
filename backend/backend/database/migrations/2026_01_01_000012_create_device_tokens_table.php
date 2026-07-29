<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('device_tokens', function (Blueprint $table) {


            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();



            /*
            |--------------------------------------------------------------------------
            | User Relation
            |--------------------------------------------------------------------------
            */

            $table->foreignId(
                'user_id'
            )
            ->constrained('users')
            ->cascadeOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Firebase Token
            |--------------------------------------------------------------------------
            */

            $table->text(
                'device_token'
            )
            ->unique();



            /*
            |--------------------------------------------------------------------------
            | Device Information
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'device_type',
                [
                    'mobile',
                    'tablet',
                    'web'
                ]
            )
            ->default('mobile');



            $table->enum(
                'platform',
                [
                    'android',
                    'ios',
                    'web'
                ]
            );



            $table->string(
                'app_version'
            )
            ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Usage Tracking
            |--------------------------------------------------------------------------
            */

            $table->timestamp(
                'last_used_at'
            )
            ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Token Status
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'status',
                [
                    'active',
                    'inactive',
                    'expired'
                ]
            )
            ->default('active');



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
            'device_tokens'
        );

    }

};