<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')
                 ->references('id')->on('products')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');

            $table->bigInteger('order_id')->unsigned();
            $table->foreign('order_id')
                 ->references('id')->on('orders')
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
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropForeign('order_products_product_id_foreign');
            $table->dropForeign('order_products_order_id_foreign');
        });
    }
}
