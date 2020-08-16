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
        Schema::create('order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code',100)->nullable();
            $table->string('user_name');
            $table->string('user_phone');
            $table->string('user_address');
            $table->date('pickup_date')->nullable();
            $table->time('pickup_time_from')->nullable();
            $table->time('pickup_time_to')->nullable();
            $table->string('message');
            $table->enum('state', ['new', 'in_progress', 'delivered', 'cancel'])->default('new');
            $table->enum('payment_type', ['card', 'cash', 'paypal'])->default('cash');
            $table->enum('payment_state', ['done', 'undone', 'confirmed'])->default('undone');
            $table->enum('delivery_type', ['express','standard'])->default('standard');
            $table->bigInteger('messenger_id')->nullable()->unsigned();
            $table->bigInteger('municipie_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->double('transportation_cost', 8, 2)->nullable();
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
        Schema::dropIfExists('order');
    }
}
