<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;



return new class extends Migration
{


    public function up(): void
    {


        Schema::create('odontograms', function(Blueprint $table){


            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Relationship
            |--------------------------------------------------------------------------
            */


            $table->foreignId('patient_id')

                ->constrained('patients')

                ->cascadeOnDelete();



            $table->foreignId('dental_record_id')

                ->nullable()

                ->constrained('dental_records')

                ->nullOnDelete();



            $table->foreignId('recorded_by')

                ->nullable()

                ->constrained('users')

                ->nullOnDelete();





            /*
            |--------------------------------------------------------------------------
            | Tooth Information
            |--------------------------------------------------------------------------
            */


            $table->string('tooth_number');



            $table->enum(

                'dentition',

                [

                    'permanent',

                    'primary'

                ]

            )

            ->default('permanent');





            /*
            |--------------------------------------------------------------------------
            | Tooth Condition
            |--------------------------------------------------------------------------
            */


            $table->enum(

                'condition',

                [

                    'normal',

                    'caries',

                    'filled',

                    'missing',

                    'extracted',

                    'root_canal',

                    'crown',

                    'implant',

                    'fracture',

                    'other'

                ]

            )

            ->default('normal');





            /*
            |--------------------------------------------------------------------------
            | Tooth Surface
            |--------------------------------------------------------------------------
            */


            $table->json('surface_status')

                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Clinical Information
            |--------------------------------------------------------------------------
            */


            $table->text('diagnosis')

                ->nullable();



            $table->text('treatment_plan')

                ->nullable();



            $table->text('note')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Examination Date
            |--------------------------------------------------------------------------
            */


            $table->date('exam_date');





            /*
            |--------------------------------------------------------------------------
            | Integration
            |--------------------------------------------------------------------------
            */


            $table->string('mypcu_odontogram_id')

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


            $table->index('patient_id');

            $table->index('tooth_number');

            $table->index('condition');



        });


    }





    public function down(): void
    {

        Schema::dropIfExists('odontograms');

    }


};