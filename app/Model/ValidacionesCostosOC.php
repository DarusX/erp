<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ValidacionesCostosOC extends Model
{

    protected $table = "validaciones_costos_ordenes_compra";

    protected $fillable = [
        "orden_id",
        "rol_id",
        "jerarquia",
        "estado"
    ];

}
