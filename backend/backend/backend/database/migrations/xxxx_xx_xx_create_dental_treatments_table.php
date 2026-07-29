<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;



return new class extends Migration
{


    public function up(): void
    {


        Schema::create('dental_treatments', function(Blueprint $table){


            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Relationship
            |--------------------------------------------------------------------------
            */


            $table->foreignId('dental_record_id')

                ->constrained('dental_records')

                ->cascadeOnDelete();



            $table->foreignId('service_id')

                ->nullable()

                ->constrained('dental_services')

                ->nullOnDelete();



            $table->foreignId('provider_id')

                ->nullable()

                ->constrained('users')

                ->nullOnDelete();





            /*
            |--------------------------------------------------------------------------
            | Treatment Date
            |--------------------------------------------------------------------------
            */


            $table->date('treatment_date');





            /*
            |--------------------------------------------------------------------------
            | Tooth Information
            |--------------------------------------------------------------------------
            */


            $table->string('tooth_number')

                ->nullable();



            $table->enum(

                'tooth_type',

                [

                    'permanent',

                    'primary'

                ]

            )

            ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Treatment Detail
            |--------------------------------------------------------------------------
            */


            $table->string('procedure_name');



            $table->text('procedure_detail')

                ->nullable();



            $table->string('material_used')

                ->nullable();



            $table->string('diagnosis_code')

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



            $table->decimal(

                'paid_amount',

                10,

                2

            )

            ->default(0);





            /*
            |--------------------------------------------------------------------------
            | Treatment Result
            |--------------------------------------------------------------------------
            */


            $table->enum(

                'result',

                [

                    'success',

                    'follow_up',

                    'refer'

                ]

            )

            ->default('success');



            $table->text('note')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Integration
            |--------------------------------------------------------------------------
            */


            $table->string('mypcu_treatment_id')

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


            $table->index('dental_record_id');

            $table->index('treatment_date');

            $table->index('procedure_name');



        });


    }





    public function down(): void
    {

        Schema::dropIfExists('dental_treatments');

    }


};