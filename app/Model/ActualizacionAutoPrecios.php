<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ActualizacionAutoPrecios extends Model
{

    protected $table = "actualizacion_automatica_precios";

    protected $fillable = [
        "producto_id",
        "costo_anterior",
        "costo_nuevo",
        "fecha_actualizacion",
        "estado"
    ];

}
