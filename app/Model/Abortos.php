<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Abortos extends Model
{
    protected $table = "agr_abortos";

    protected $fillable = [
        "animal_id",
        "comentarios",
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

        $query = $this->leftJoin("agr_animal as a", "a.id", "=", "agr_abortos.animal_id");
        $query->leftJoin("rh_empleados as ec", "ec.id_empleado", "=", "agr_abortos.empleado_captura_id");
        $query->leftJoin("rh_empleados as ev", "ev.id_empleado", "=", "agr_abortos.empleado_valida_id");
        $query->leftJoin("rh_empleados as ea", "ea.id_empleado", "=", "agr_abortos.empleado_autoriza_id");

        $query->select(
            "agr_abortos.*",
            "a.numero",
            \DB::raw("concat(ec.nombre, ' ', ec.apaterno, ' ', ec.amaterno) as empleado_captura"),
            \DB::raw("concat(ev.nombre, ' ', ev.apaterno, ' ', ev.amaterno) as empleado_valida"),
            \DB::raw("concat(ea.nombre, ' ', ea.apaterno, ' ', ea.amaterno) as empleado_autoriza")
        );

        if(!empty($datos["animal_id"])){
            $query->where("agr_abortos.animal_id", $datos["animal_id"]);
        }

        if(!empty($datos["id"])){
            $query->where("agr_abortos.id", $datos["id"]);
            if(!empty($datos["first"])){
                return $query->first();
            }
        }

        return $query->get();

    }
}
