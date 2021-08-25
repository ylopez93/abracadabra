<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeingKeyLocalityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('localities', function (Blueprint $table) {
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

        Schema::table('localities', function (Blueprint $table) {
            $table->dropForeign('localities_municipies_id_foreign');
        });
    }
}