<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EmpleadosMonto extends Model
{
    protected $table = "rh_empleados_monto";

    protected $fillable = [
        "empleado_id",
        "monto_bono",
        "monto_bono_respaldo",
        "monto_anterior",
        "estatus"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado", "=", "rh_empleados_monto.empleado_id");

        $query->select(
            "rh_empleados_monto.*",
            \DB::raw("ifnull(rh_empleados_monto.monto_bono,0) as monto_bono"),
            \DB::raw("ifnull(rh_empleados_monto.monto_bono_respaldo,0) as monto_bono_respaldo"),
            \DB::raw("ifnull(rh_empleados_monto.monto_anterior,0) as monto_anterior"),
            \DB::raw("concat(e.nombre, ' ', e.apaterno, ' ', e.amaterno) as nombre_completo"));

        if(!empty($datos["empleado_id"])){
            $query->where("empleado_id", $datos["empleado_id"]);
            if(!empty($datos["first"])){
                return $query->first();
            }
        }

        if(!empty($datos["estatus"])){
            $query->where("rh_empleados_monto.estatus", $datos["estatus"]);
        }

        if(!empty($datos["id"])){
            $query->where("id", $datos["id"]);
            if(!empty($datos["first"])){
                return $query->first();
            }
        }

        //dd($query->toSql());

        return $query->get();

    }
}
