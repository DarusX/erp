<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PresupuestoAgropecuaria extends Model
{
    protected $table = "agr_presupuesto_solicitud";

    protected $fillable = [
        "anio_captura",
        "mes_captura",
        "total",
        "monto_respaldo",
        "general",
        "general_respaldo",
        "empleado_captura_id",
        "fecha_captura",
        "empleado_valida_id",
        "fecha_valida",
        "empleado_autoriza_id",
        "fecha_autoriza",
        "estatus"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("rh_empleados as ec", "ec.id_empleado", "=", "agr_presupuesto_solicitud.empleado_captura_id");
        $query->leftJoin("rh_empleados as ev", "ev.id_empleado", "=", "agr_presupuesto_solicitud.empleado_valida_id");
        $query->leftJoin("rh_empleados as ea", "ea.id_empleado", "=", "agr_presupuesto_solicitud.empleado_autoriza_id");

        $query->select(
            "agr_presupuesto_solicitud.*",
            \DB::raw("ifnull(agr_presupuesto_solicitud.total,0) as total"),
            \DB::raw("ifnull(agr_presupuesto_solicitud.monto_respaldo,0) as monto_respaldo"),
            \DB::raw("concat(ec.nombre, ' ', ec.apaterno, ' ', ec.amaterno) as empleado_captura"),
            \DB::raw("ifnull(concat(ev.nombre, ' ', ev.apaterno, ' ', ev.amaterno),'') as empleado_valida"),
            \DB::raw("ifnull(concat(ea.nombre, ' ', ea.apaterno, ' ', ea.amaterno),'') as empleado_autoriza")
        );

        if(!empty($datos["anio"])){
            $query->where("anio_captura", $datos["anio"]);
        }
        if(!empty($datos["mes"])){
            $query->where("mes_captura", $datos["mes"]);
        }
        if(!empty($datos["id"])){
            $query->where("agr_presupuesto_solicitud.id", $datos["id"]);
            if(!empty($datos{"first"})){
                return $query->first();
            }
        }

        if(!empty($datos["first"])){
            return $query->first();
        }

        return $query->get();

    }
}
