<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_movements', function (Blueprint $table) {
            $table->id();
            $table->integer('storeID');
            $table->integer('productID');
            $table->integer('measurementID');
            $table->integer('projectID');
            $table->date('transactionDate');
            $table->string('moveIn');
            $table->string('moveOut');
            $table->string('description');
            $table->string('orderNo');
            $table->integer('quantity');
            $table->integer('isConfirmed')->nullable();
            $table->integer('isConfirmedBy')->nullable();
            $table->integer('hasColor')->nullable();
            $table->string('image');
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
        Schema::dropIfExists('product_movements');
    }
}
