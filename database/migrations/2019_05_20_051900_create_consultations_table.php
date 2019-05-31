<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('patient_id');
            $table->integer('registration_id');
            $table->string('complains')->nullable();
            $table->string('findings')->nullable();
            $table->string('physical_examination')->nullable();
            $table->string('other_diagnosis')->nullable();
            $table->string('detain_admit')->nullable();
            $table->string('labs')->nullable();
            $table->string('ultra_sound_scan')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('consultations');
    }
}
