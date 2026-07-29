<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('audit_logs', function (Blueprint $table) {


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
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Action Information
            |--------------------------------------------------------------------------
            */

            $table->string(
                'action'
            );


            /*
            |--------------------------------------------------------------------------
            | Module Name
            |--------------------------------------------------------------------------
            */

            $table->string(
                'module'
            );



            /*
            |--------------------------------------------------------------------------
            | Reference Record
            |--------------------------------------------------------------------------
            */

            $table->string(
                'record_type'
            )
            ->nullable();



            $table->unsignedBigInteger(
                'record_id'
            )
            ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Change Data
            |--------------------------------------------------------------------------
            */

            $table->json(
                'old_values'
            )
            ->nullable();



            $table->json(
                'new_values'
            )
            ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Security Information
            |--------------------------------------------------------------------------
            */

            $table->string(
                'ip_address',
                45
            )
            ->nullable();



            $table->text(
                'user_agent'
            )
            ->nullable();



            $table->timestamps();



            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index([
                'module',
                'action'
            ]);


            $table->index(
                'record_id'
            );


        });

    }



    public function down(): void
    {

        Schema::dropIfExists(
            'audit_logs'
        );

    }

};