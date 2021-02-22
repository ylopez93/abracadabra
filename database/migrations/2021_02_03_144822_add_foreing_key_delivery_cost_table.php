<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeingKeyDeliveryCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deliveries_costs', function (Blueprint $table) {
            $table->bigInteger('from_municipality_id')->unsigned();
            $table->foreign('from_municipality_id')
                 ->references('id')->on('municipies')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');

            $table->bigInteger('to_municipality_id')->unsigned();
            $table->foreign('to_municipality_id')
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
        Schema::table('deliveries_costs', function (Blueprint $table) {
            $table->dropForeign('deliveries_costs_from_municipality_id_foreign');
            $table->dropForeign('deliveries_costs_to_municipality_id_foreign');
        });
    }
}