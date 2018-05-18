<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EntradaConfirmacionProvDetPartidas extends Model
{

    protected $table = "entrada_confirmacion_proveedores_detalles_partidas";

    protected $primaryKey = "id_entrada_confirmacion_proveedores_detalles_partidas";

    public $timestamps = false;

}
