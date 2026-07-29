<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create(
            'personal_access_tokens',
            function (Blueprint $table) {


                /*
                |--------------------------------------------------------------------------
                | Primary Key
                |--------------------------------------------------------------------------
                */

                $table->id();



                /*
                |--------------------------------------------------------------------------
                | Token Owner
                |--------------------------------------------------------------------------
                */

                $table->morphs(
                    'tokenable'
                );



                /*
                |--------------------------------------------------------------------------
                | Token Information
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'name'
                );


                $table->string(
                    'token',
                    64
                )
                ->unique();



                /*
                |--------------------------------------------------------------------------
                | Token Permission
                |--------------------------------------------------------------------------
                */

                $table->text(
                    'abilities'
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
                | Expiration
                |--------------------------------------------------------------------------
                */

                $table->timestamp(
                    'expires_at'
                )
                ->nullable();



                /*
                |--------------------------------------------------------------------------
                | Timestamp
                |--------------------------------------------------------------------------
                */

                $table->timestamps();


            }
        );

    }



    /**
     * Reverse migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists(
            'personal_access_tokens'
        );

    }

};