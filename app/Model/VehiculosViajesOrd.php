<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VehiculosViajesOrd extends Model
{

    protected $table = "vehiculos_viajes_ordenes";

    protected $primaryKey = "id_vehiculo_viaje_orden";

    protected $fillable = [
        "id_usuario",
        "id_vehiculo",
        "id_sucursal",
        "id_chofer",
        "tipo",
        "salida",
        "enlonada",
        "fecha_captura",
        "fecha_finalizacion",
        "estatus",
        "pagado",
        "comentario"
    ];

}
