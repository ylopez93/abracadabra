<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
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

            $table->bigInteger('municipie_id')->unsigned();
            $table->foreign('municipie_id')
                ->references('id')->on('municipies')
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_messenger_id_foreign');
            $table->dropForeign('orders_user_id_foreign');
            $table->dropForeign('orders_municipie_id_foreign');
        });
    }
}