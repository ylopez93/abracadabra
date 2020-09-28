<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyMunicipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('municipies', function (Blueprint $table) {
            $table->bigInteger('province_id')->unsigned();
            $table->foreign('province_id')
                 ->references('id')->on('provinces')
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
        Schema::table('municipies', function (Blueprint $table) {
            $table->dropForeign('municipies_province_id_foreign');
        });
    }
}
