<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CapturaMaxMin extends Model
{

    protected $table = "capturas_max_min";

    protected $fillable = [
        "id_existencia",
        "maximo_anterior",
        "maximo_nuevo",
        "minimo_anterior",
        "minimo_nuevo",
        "empleado_captura_id",
        "fecha_captura"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("almacenes_existencias as ae", "ae.id_existencia", "=", "capturas_max_min.id_existencia");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "capturas_max_min.empleado_captura_id");

        $query->select(
            "capturas_max_min.*",
            \DB::raw("ifnull(concat(e.nombre, ' ', e.apaterno, ' ', e.amaterno), '') as empleado")
        );

        if (!empty($datos["id_existencia"])){

            $query->where("capturas_max_min.id_existencia", $datos["id_existencia"]);

        }

        $query->orderBy("fecha_captura", "asc");

        return $query->get();

    }

}
