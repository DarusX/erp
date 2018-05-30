<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidatos', function (Blueprint $table) {
            $table->increments('id_candidato');
            $table->string('nombre');
            $table->string('email');
            $table->string('curriculum');
            $table->string('telefono');
            $table->integer('id_vacante')->unsigned();
            $table->timestamps();
            
            $table->foreign('id_vacante')->references('id_vacante')->on('vacantes')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('candidatos');
    }
}
