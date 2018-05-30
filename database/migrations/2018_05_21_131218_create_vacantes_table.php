<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacantes', function (Blueprint $table) {
            $table->increments('id_vacante');
            $table->integer('id_puesto_sucursal');
            $table->timestamps();

            $table->unique('id_puesto_sucursal');
            $table->foreign('id_puesto_sucursal')->references('id_puesto_sucursal')->on('rh_puestos_sucursales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacantes');
    }
}
