<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('dental_treatments', function (Blueprint $table) {


            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Dental Record Relation
            |--------------------------------------------------------------------------
            */

            $table->foreignId(
                'dental_record_id'
            )
            ->constrained('dental_records')
            ->cascadeOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Service Relation
            |--------------------------------------------------------------------------
            */

            $table->foreignId(
                'service_id'
            )
            ->nullable()
            ->constrained('dental_services')
            ->nullOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Dentist Relation
            |--------------------------------------------------------------------------
            */

            $table->foreignId(
                'dentist_id'
            )
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Tooth Information
            |--------------------------------------------------------------------------
            */

            $table->string(
                'tooth_number'
            )
            ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Treatment Information
            |--------------------------------------------------------------------------
            */

            $table->date(
                'treatment_date'
            );



            $table->text(
                'diagnosis'
            )
            ->nullable();



            $table->text(
                'procedure_detail'
            )
            ->nullable();



            $table->text(
                'material_used'
            )
            ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Cost
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'cost',
                10,
                2
            )
            ->default(0);



            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'status',
                [
                    'planned',
                    'in_progress',
                    'completed',
                    'cancelled'
                ]
            )
            ->default('planned');



            $table->timestamps();



            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index(
                'tooth_number'
            );


        });

    }



    public function down(): void
    {

        Schema::dropIfExists(
            'dental_treatments'
        );

    }

};