<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class cprocedimiento extends Model
{
    //
    protected $table = "procedimiento";
    protected $fillable = [
        'procedimiento',
        'objetivo',
        'version',
        'codigo',
        'proceso_id',
        'categoria_id',
        'empleado_captura_id',
        'empleado_validad_id',
        'empleado_cancela',
        'fecha_validacion',
        'fecha_cancelacion',
        'estatus',

    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("calidad_procedimiento as cp", "cp.id", "=", "procedimiento.proceso_id");
        $query->leftJoin("calidad_categoria as cc", "cc.id", "=", "procedimiento.categoria_id");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "procedimiento.empleado_captura_id");
        $query->leftJoin("rh_empleados as e2", "e2.id_empleado", "=", "procedimiento.empleado_validad_id");
        $query->leftJoin("rh_empleados as e3", "e3.id_empleado", "=", "procedimiento.empleado_cancela");

        $query->select(
            "procedimiento.*",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as empleado_captura"),
            \DB::raw("concat(e2.nombre,' ',e2.apaterno,' ',e2.amaterno) as empleado_valida"),
            \DB::raw("concat(e3.nombre,' ',e3.apaterno,' ',e3.amaterno) as empleado_cancela"),
            "cp.procedimiento as proceso", "cp.codigo as codigo_proceso",
            "cc.categoria", "cc.codigo as codigo_categoria"


        );
        if (!empty($datos["procedimiento"])) {
            $query->where("procedimiento.procedimiento", "like", "%" . $datos["procedimiento"] . "%");
        }

        if (!empty($datos["id_puesto"])) {
            $query->where(\DB::raw("(select count(*) from procedimiento_actividad_puesto as pp where pp.procedimiento_id = procedimiento.id  and pp.puesto_id =" . $datos["id_puesto"] . ")"), ">", 0)
                ->orWhere(\DB::raw("(select count(*) from procedimiento_puesto as pp2 where pp2.procedimiento_id = procedimiento.id and  pp2.puesto_id =" . $datos["id_puesto"] . ")"), ">", 0);
        }
        if (!empty($datos["categoria_id"])) {
            $query->where("procedimiento.categoria_id", "=", $datos["categoria_id"]);
        }

        if (!empty($datos["proceso_id"])) {
            $query->where("procedimiento.proceso_id", "=", $datos["proceso_id"]);
        }
        if (!empty($datos["id"])) {
            $query->where("procedimiento.id", "=", $datos["id"]);
            return $query->first();
        }
        return $query->get();
    }
}
