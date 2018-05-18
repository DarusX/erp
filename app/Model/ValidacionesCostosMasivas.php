<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ValidacionesCostosMasivas extends Model
{

    protected $table = "validaciones_costos_masivas";

    protected $fillable = [
        "modificacion_costos_masiva_id",
        "administrador_validaciones_id",
        "orden",
        "estado"
    ];

}