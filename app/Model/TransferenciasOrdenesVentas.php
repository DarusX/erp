<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TransferenciasOrdenesVentas extends Model
{

    protected $table = "transferencias_ordenes_ventas";

    protected $fillable = [
        "id_transferencia_orden",
        "id_transferencia_orden_descripcion",
        "id_venta",
        "id_venta_descripcion"
    ];

}
