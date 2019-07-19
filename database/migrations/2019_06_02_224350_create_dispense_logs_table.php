<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDispenseLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispense_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('registration_id');
            $table->integer('patient_id');
            $table->integer('drugs_id');
            $table->decimal('amount_paid')->default(0);
            $table->decimal('total_amount_to_pay')->default(0);
            $table->string('dispensed_by');
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
        Schema::dropIfExists('dispense_logs');
    }
}
