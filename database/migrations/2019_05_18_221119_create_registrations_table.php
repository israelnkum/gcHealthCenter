<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('patient_id');
            $table->boolean('isInsured')->default(0);
            $table->string('insurance_type',50)->nullable();
            $table->string('insurance_number',50)->nullable();
            $table->decimal('insurance_amount')->nullable();
            $table->decimal('registration_fee');
            $table->boolean('vitals')->default(0);
            $table->boolean('consult')->default(0);
            $table->boolean('review')->default(0);
            $table->boolean('medication')->default(0);
            $table->boolean('detain')->default(0);
            $table->boolean('hasArrears')->default(0);
            $table->boolean('hasRecords')->default(0);
            $table->string('discharged_date',30)->nullable();
            $table->integer('user_id');
            $table->boolean('old_patient')->default(0);
            $table->string('last_visit',30)->nullable();
            $table->string('type',20)->default('Consultation')->nullable();
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
        Schema::dropIfExists('registrations');
    }
}
