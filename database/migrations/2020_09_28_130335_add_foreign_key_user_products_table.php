<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyUserProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_products', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                 ->references('id')->on('users')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');

            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')
                 ->references('id')->on('products')
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
        Schema::table('user_products', function (Blueprint $table) {
            $table->dropForeign('user_products_user_id_foreign');
            $table->dropForeign('user_products_product_id_foreign');
        });
    }
}
