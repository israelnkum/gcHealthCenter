<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('bill_id')->nullable();
            $table->integer('registration_id');
            $table->integer('patient_id');
            $table->integer('drugs_id');
            $table->string('dosage',200);
            $table->string('days',20);
            $table->integer('qty')->default(0);
            $table->integer('qty_dispensed')->default(0);
            $table->boolean('dispensed')->default(0);
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
        Schema::dropIfExists('medications');
    }
}
