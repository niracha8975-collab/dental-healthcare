<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('odontograms', function (Blueprint $table) {


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

            $table->foreignId(
                'patient_id'
            )
            ->constrained('patients')
            ->cascadeOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Dental Record Relation
            |--------------------------------------------------------------------------
            */

            $table->foreignId(
                'dental_record_id'
            )
            ->nullable()
            ->constrained('dental_records')
            ->nullOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Tooth Information
            |--------------------------------------------------------------------------
            */

            $table->string(
                'tooth_number'
            );



            $table->enum(
                'dentition_type',
                [
                    'primary',
                    'permanent'
                ]
            )
            ->default('permanent');



            /*
            |--------------------------------------------------------------------------
            | Tooth Status
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'tooth_status',
                [
                    'normal',
                    'caries',
                    'filled',
                    'missing',
                    'extracted',
                    'root_canal',
                    'crown',
                    'other'
                ]
            )
            ->default('normal');



            /*
            |--------------------------------------------------------------------------
            | Surface Data
            |--------------------------------------------------------------------------
            |
            | JSON เก็บ M,O,D,B,L,P
            |
            */

            $table->json(
                'surface_data'
            )
            ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Additional Note
            |--------------------------------------------------------------------------
            */

            $table->text(
                'note'
            )
            ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Examiner
            |--------------------------------------------------------------------------
            */

            $table->foreignId(
                'examined_by'
            )
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();



            $table->date(
                'examined_date'
            );



            $table->timestamps();



            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index([
                'patient_id',
                'tooth_number'
            ]);


        });

    }



    public function down(): void
    {

        Schema::dropIfExists(
            'odontograms'
        );

    }

};