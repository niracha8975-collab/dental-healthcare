<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;



return new class extends Migration
{


    public function up(): void
    {


        Schema::create('settings', function(Blueprint $table){


            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Setting Key
            |--------------------------------------------------------------------------
            */


            $table->string('key')

                ->unique();



            /*
            |--------------------------------------------------------------------------
            | Setting Value
            |--------------------------------------------------------------------------
            */


            $table->longText('value')

                ->nullable();



            $table->enum(

                'type',

                [

                    'string',

                    'number',

                    'boolean',

                    'json',

                    'image'

                ]

            )

            ->default('string');





            /*
            |--------------------------------------------------------------------------
            | Group
            |--------------------------------------------------------------------------
            */


            $table->string('group')

                ->default('general');





            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */


            $table->text('description')

                ->nullable();





            /*
            |--------------------------------------------------------------------------
            | Public Access
            |--------------------------------------------------------------------------
            */


            $table->boolean('is_public')

                ->default(false);





            /*
            |--------------------------------------------------------------------------
            | Editable
            |--------------------------------------------------------------------------
            */


            $table->boolean('is_editable')

                ->default(true);





            /*
            |--------------------------------------------------------------------------
            | Creator
            |--------------------------------------------------------------------------
            */


            $table->foreignId('created_by')

                ->nullable()

                ->constrained('users')

                ->nullOnDelete();



            $table->foreignId('updated_by')

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


            $table->index('group');

            $table->index('is_public');



        });


    }





    public function down(): void
    {

        Schema::dropIfExists('settings');

    }


};