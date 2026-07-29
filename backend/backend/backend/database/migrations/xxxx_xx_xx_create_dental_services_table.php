<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;



return new class extends Migration
{


    public function up(): void
    {


        Schema::create('dental_services', function(Blueprint $table){


            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Service Identity
            |--------------------------------------------------------------------------
            */


            $table->string('code')

                ->unique();



            $table->string('name');



            $table->text('description')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Service Category
            |--------------------------------------------------------------------------
            */


            $table->enum(

                'category',

                [

                    'examination',

                    'preventive',

                    'restorative',

                    'surgery',

                    'periodontal',

                    'pediatric',

                    'prosthodontics',

                    'other'

                ]

            )

            ->default('examination');





            /*
            |--------------------------------------------------------------------------
            | Treatment Information
            |--------------------------------------------------------------------------
            */


            $table->integer('duration_minutes')

                ->default(30);



            $table->decimal(

                'price',

                10,

                2

            )

            ->default(0);





            /*
            |--------------------------------------------------------------------------
            | Queue Configuration
            |--------------------------------------------------------------------------
            */


            $table->integer('default_queue_limit')

                ->default(20);





            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */


            $table->enum(

                'status',

                [

                    'active',

                    'inactive'

                ]

            )

            ->default('active');





            /*
            |--------------------------------------------------------------------------
            | Display
            |--------------------------------------------------------------------------
            */


            $table->integer('sort_order')

                ->default(0);



            $table->string('icon')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Creator
            |--------------------------------------------------------------------------
            */


            $table->foreignId('created_by')

                ->nullable()

                ->constrained('users')

                ->nullOnDelete();





            $table->timestamps();



            $table->softDeletes();



            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */


            $table->index('category');

            $table->index('status');



        });


    }





    public function down(): void
    {

        Schema::dropIfExists('dental_services');

    }


};