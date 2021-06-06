<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderExpressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_expresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code',100)->nullable();
            $table->string('name_r');
            $table->string('address_r');
            $table->string('cell_r')->nullable();
            $table->string('phone_r')->nullable();
            $table->string('name_d');
            $table->string('address_d');
            $table->string('cell_d')->nullable();
            $table->string('phone_d')->nullable();
            $table->string('object_details');
            $table->string('weigth')->nullable();
            $table->enum('state', ['nueva', 'en_progreso','asignada','entregada', 'cancelada'])->default('nueva');
            $table->string('message')->nullable();
            $table->string('message_cancel')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_expresses');
    }
}