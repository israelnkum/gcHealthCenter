<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('registration_number')->unique();;
            $table->string('folder_number')->unique();;
            $table->string('title');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('other_name')->nullable();
            $table->string('date_of_birth');
            $table->integer('age');
            $table->string('gender');
            $table->string('marital_status');
            $table->string('other_information')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('house_number')->nullable();
            $table->string('locality')->nullable();
            $table->string('phone_number');
            $table->string('occupation');
            $table->string('religion');
            $table->string('name_of_nearest_relative')->nullable();
            $table->string('number_of_nearest_relative')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
