<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EmpleadosRanchos extends Model
{
    protected $table = "agr_empleados_ranchos";

    protected $fillable = [
        "empleado_id",
        "rancho_id"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado", "=", "agr_empleados_ranchos.empleado_id");
        $query->leftJoin("agr_rancho as r", "r.id", "=", "agr_empleados_ranchos.rancho_id");

        $query->select(
            "agr_empleados_ranchos.*",
            \DB::raw("concat(e.nombre, ' ', e.apaterno, ' ', e.amaterno) as nombre_completo"),
            "r.rancho"
        );

        if(!empty($datos["rancho_id"])){
            $query->where("agr_empleados_ranchos.rancho_id", $datos["rancho_id"]);
        }
        if(!empty($datos["empleado_id"])){
            $query->where("agr_empleados_ranchos.empleado_id", $datos["empleado_id"]);
        }

        return $query->get();

    }
}
