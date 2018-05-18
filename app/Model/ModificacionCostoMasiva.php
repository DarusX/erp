<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ModificacionCostoMasiva extends Model
{

    protected $table = "modificacion_costos_masiva";

    protected $fillable = [
        "cantidad_productos",
        "usuario_captura_id",
        "fecha_captura",
        "estado"
    ];

}