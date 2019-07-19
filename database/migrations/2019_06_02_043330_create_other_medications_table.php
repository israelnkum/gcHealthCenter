<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtherMedicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_medications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('registration_id');
            $table->integer('patient_id');
            $table->string('drug');
            $table->string('dosage');
            $table->string('type',20)->nullable();
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
        Schema::dropIfExists('other_medications');
    }
}
