<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drugs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('drug_type_id');
            $table->string('unit_of_pricing');
            $table->integer('quantity_in_stock')->default(0)->nullable();
            $table->decimal('cost_price');
            $table->integer('supplier_id');
            $table->decimal('retail_price');
            $table->decimal('nhis_amount');
            $table->string('expiry_date')->nullable();
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
        Schema::dropIfExists('drugs');
    }
}
