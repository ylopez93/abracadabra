<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries_costs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('latitude_from',10,8);
            $table->decimal('longitude_from',11,8);
            $table->decimal('latitude_to',10,8);
            $table->decimal('longitude_to',11,8);
            $table->double('tranpostation_cost', 8, 2);
            $table->double('distance',8,2);
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
        Schema::dropIfExists('deliveries_costs');
    }
}
