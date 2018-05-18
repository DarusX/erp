<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SucursalesPendientesImprimirDetalles extends Model
{

    protected $table = "sucursales_pendientes_imprimir_detalles";

    protected $fillable = [
        "sucursal_pendiente_imprimir_id",
        "producto_id",
        "codigo_producto",
        "descripcion",
        "linea",
        "precio_publico",
        "precio_distribuidor"
    ];

}
