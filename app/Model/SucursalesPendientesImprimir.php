<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SucursalesPendientesImprimir extends Model
{

    protected $table = "sucursales_pendientes_imprimir";

    protected $fillable = [
        "sucursal_id",
        "cantidad_etiquetas",
        "estado"
    ];

}
