<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ModificacionCostoMasivaDetalles extends Model
{

    protected $table = "modificacion_costos_masiva_detalles";

    protected $fillable = [
        "modificacion_costos_masiva_id",
        "producto_id",
        "costo_anterior",
        "costo_nuevo",
        "porcentaje_aumento",
        "cantidad_aumento",
        "estado"
    ];

}
