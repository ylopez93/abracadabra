<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderMototaxiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('orders_mototaxis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code',100)->nullable();
            $table->string('cell');
            $table->string('address_from');
            $table->string('address_to');
            $table->enum('state', ['nueva', 'en_progreso','asignada','entregada', 'cancelada'])->default('nueva');
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
        Schema::dropIfExists('orders_mototaxis');
    }
}
