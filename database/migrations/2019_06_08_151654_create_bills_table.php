<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('registration_id');
            $table->integer('patient_id');
            $table->string('item');
            $table->decimal('amount')->default(0);
            $table->decimal('insurance_amount')->default(0);
            $table->decimal('total_amount_to_pay')->default(0);
            $table->string('billed_by',199);
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
        Schema::dropIfExists('bills');
    }
}
