<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeingKeyApplicationJobsImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_jobs_images', function (Blueprint $table) {
            $table->bigInteger('application_jobs_id')->unsigned();
            $table->foreign('application_jobs_id')
                 ->references('id')->on('application_jobs')
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
        Schema::table('application_jobs_images', function (Blueprint $table) {
            $table->dropForeign('application_jobs_images_application_jobs_id_foreign');
        });
    }
}
