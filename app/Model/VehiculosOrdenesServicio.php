<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VehiculosOrdenesServicio extends Model
{
    
    protected $table = "vehiculos_ordenes_servicio";

    protected $primaryKey = "id_orden_servicio";

    public $timestamps = false;

    protected $fillable = [
        "id_vehiculo",
        "id_sucursal",
        "id_usuario_captura",
        "id_usuario_procesa",
        "id_usuario_audita",
        "titulo",
        "descripcion",
        "observacion",
        "fecha_captura",
        "fecha_procesa",
        "fecha_finalizacion",
        "fecha_auditoria",
        "dias_estimados",
        "tipo",
        "estatus",
        "comentarioauditoria",
        "auditoria",
        "categoria",
        "causa",
        "nivel",
        "id_chofer",
        "taller"
    ];
    
}
