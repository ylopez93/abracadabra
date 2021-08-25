<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code',100)->nullable();
            $table->string('user_name');
            $table->string('user_phone');
            $table->string('user_address');
            $table->date('pickup_date')->nullable();
            $table->time('pickup_time_from')->nullable();
            $table->time('pickup_time_to')->nullable();
            $table->time('delivery_time_to')->nullable();
            $table->time('delivery_time_from')->nullable();
            $table->string('message')->nullable();
            $table->enum('state', ['nueva', 'en_progreso','asignada','entregada', 'cancelada'])->default('nueva');
            $table->enum('payment_type', ['card', 'cash', 'paypal'])->default('cash');
            $table->enum('payment_state', ['done', 'undone', 'confirmed'])->default('undone');
            $table->enum('delivery_type', ['express','standard'])->default('standard');
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
        Schema::dropIfExists('orders');
    }
}