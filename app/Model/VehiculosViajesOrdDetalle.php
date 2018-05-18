<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VehiculosViajesOrdDetalle extends Model
{
    protected $table = "vehiculos_viajes_ordenes_detalles";

    protected $primaryKey = "id_vehiculo_orden_descripcion";

    protected $fillable = [
        "id_vehiculo_viaje_orden",
        "id_orden_descripcion",
        "id_orden",
        "id_almacen",
        "id_producto",
        "cantidad",
        "estatus"
    ];
}
