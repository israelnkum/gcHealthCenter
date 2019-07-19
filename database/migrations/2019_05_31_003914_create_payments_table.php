<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('registration_id');
            $table->integer('patient_id');
            $table->decimal('drugs_total')->default(0);
            $table->decimal('service_total')->default(0);
            $table->decimal('grand_total')->default(0);
            $table->decimal('amount_paid')->default(0);
            $table->decimal('arrears')->default(0);
            $table->decimal('change')->default(0);
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
        Schema::dropIfExists('payments');
    }
}
