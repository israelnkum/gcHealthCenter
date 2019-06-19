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
            $table->string('folder_number',20)->unique();;
            $table->string('title',5);
            $table->string('first_name',199);
            $table->string('last_name',199);
            $table->string('other_name',199)->nullable();
            $table->string('date_of_birth',20);
            $table->integer('age');
            $table->string('gender',10);
            $table->string('marital_status',15);
            $table->string('other_information',255)->nullable();
            $table->string('postal_address',199)->nullable();
            $table->string('house_number',50)->nullable();
            $table->string('locality',199)->nullable();
            $table->string('phone_number',50);
            $table->string('occupation',199);
            $table->string('religion',199);
            $table->string('name_of_nearest_relative',255)->nullable();
            $table->string('number_of_nearest_relative',50)->nullable();
            $table->string('last_visit',25)->nullable();
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
