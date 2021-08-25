<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeingKeyOrdersExpressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders_expresses', function (Blueprint $table) {
            $table->bigInteger('locality_id_r')->unsigned();
            $table->foreign('locality_id_r')
                 ->references('id')->on('localities')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');

            $table->bigInteger('locality_id_d')->unsigned();
            $table->foreign('locality_id_d')
                    ->references('id')->on('localities')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->bigInteger('delivery_cost_id')->unsigned();
            $table->foreign('delivery_cost_id')
                    ->references('id')->on('deliveries_costs')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->bigInteger('messenger_id')->unsigned();
            $table->foreign('messenger_id')
                    ->references('id')->on('messengers')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders_expresses', function (Blueprint $table) {
            $table->dropForeign('orders_expresses_locality_id_r_foreign');
            $table->dropForeign('orders_expresses_locality_id_d_foreign');
            $table->dropForeign('orders_expresses_delivery_cost_id_foreign');
            $table->dropForeign('orders_expresses_messenger_id_foreign');
            $table->dropForeign('orders_expresses_user_id_foreign');


        });
    }
}