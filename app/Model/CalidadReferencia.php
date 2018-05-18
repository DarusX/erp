<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CalidadReferencia extends Model
{
    //
    protected $table = "calidad_referencia";
    protected $fillable = [
        'referencia',
        'empleado_captura_id',
        'estatus',

    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado", "=", "calidad_referencia.empleado_captura_id");

        if (!empty($datos["referencia"])) {
            $query->where("referencia", "like", "%" . $datos["referencia"] . "%");
        }
        if (!empty($datos["estatus"])) {
            $query->where("calidad_referencia.estatus", "=", $datos["estatus"]);
        }

        if (!empty($datos["procedimiento_id"])) {
            $query->leftJoin("procedimiento_referencia as pr", function ($join) use ($datos) {
                $join->on("pr.referencia_id", "=", "calidad_referencia.id")
                    ->where("pr.procedimiento_id", "=", $datos["procedimiento_id"]);

            });
            $query->select(
                "calidad_referencia.*",
                \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as nombre_completo"),
                \DB::raw("if(pr.id is null,'NO','SI') as asignado")

            );

        } else {
            $query->select(
                "calidad_referencia.*",
                \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as nombre_completo"),
                \DB::raw("'No' as asignado")

            );
        }


        if (!empty($datos["id"])) {
            $query->where("calidad_referencia.id", "=", $datos["id"]);
            return $query->first();

        }


        return $query->get();
    }

    public function buscarAsignados($datos)
    {
        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado", "=", "calidad_referencia.empleado_captura_id");
        $query->leftJoin("procedimiento_referencia as pr", function ($join) use ($datos) {
            $join->on("pr.referencia_id", "=", "calidad_referencia.id")
                ->where("pr.procedimiento_id", "=", $datos["procedimiento_id"]);
        });

        if (!empty($datos["asignados"])) {
            $query->where("pr.procedimiento_id", "=", $datos["procedimiento_id"]);

        }

        if (!empty($datos["referencia"])) {
            $query->where("referencia", "like", "%" . $datos["referencia"] . "%");
        }
        if (!empty($datos["estatus"])) {
            $query->where("calidad_referencia.estatus", "=", $datos["estatus"]);
        }
        $query->select(
            "calidad_referencia.*",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as nombre_completo"),
            \DB::raw("if(pr.id is null,'NO','SI') as asignado"),
            "pr.id as procedimiento_referencia_id"

        );

        if (!empty($datos["id"])) {
            $query->where("calidad_referencia.id", "=", $datos["id"]);
            return $query->first();

        }


        return $query->get();
    }
}
