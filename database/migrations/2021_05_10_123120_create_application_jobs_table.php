<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('surname');
            $table->string('phone');
            $table->string('email');
            $table->string('ci');
            $table->string('address');
            $table->string('vehicle_registration')->nullable();
            $table->enum('state', ['aprobado','pendiente_entrevista','rechazado'])->default('pendiente_entrevista');
            $table->enum('employee_type', ['mensajero'])->default('mensajero');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_jobs');
    }
}