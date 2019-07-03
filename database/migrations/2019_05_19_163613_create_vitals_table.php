<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vitals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('registration_id');
            $table->integer('patient_id');
            $table->string('blood_pressure')->nullable()->default(0);
            $table->decimal('weight')->nullable()->default(0);
            $table->decimal('temperature')->nullable()->default(0);
            $table->decimal('pulse')->nullable()->default(0);
            $table->string('RDT',20)->nullable()->default('-');
            $table->decimal('glucose')->nullable()->default(0);
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
        Schema::dropIfExists('vitals');
    }
}
