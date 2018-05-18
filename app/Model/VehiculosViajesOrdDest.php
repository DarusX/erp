<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VehiculosViajesOrdDest extends Model
{

    protected $table = "vehiculos_viajes_ordenes_destinos";

    protected $primaryKey = "id_viaje_destino";

    protected $fillable = [
        "id_vehiculo_viaje_orden",
        "id_sucursal_origen",
        "id_sucursal_destino",
        "fecha_llegada",
        "fecha_recibido",
        "kilometraje",
        "posicion",
        "costo",
        "estatus"
    ];

}
