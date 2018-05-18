<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PlanTrabajoObservacion extends Model
{
    //
    protected $table = "calidad_plan_trabajo_observacion";
    protected $fillable = [
        "plan_id",
        "complemento_id",
        "observacion_id"

    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("calidad_observacion", "calidad_observacion.id", "=", "calidad_plan_trabajo_observacion.observacion_id");
        $query->leftJoin("calidad_observacion_nivel as cn", "cn.id", "=", "calidad_observacion.nivel_id");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "calidad_observacion.empleado_responsable_id");
        $query->leftJoin("rh_empleados as e2", "e2.id_empleado", "=", "calidad_observacion.empleado_captura_id");
        $query->leftJoin("calidad_observacion_funcion as ef", "ef.observacion_id", "=", "calidad_observacion.id");
        $query->leftJoin("rh_funciones as f", "f.id_funcion", "=", "ef.funcion_id");
        $query->leftJoin("calidad_observacion_procedimiento as op", "op.observacion_id", "=", "calidad_observacion.id");
        $query->leftJoin("rh_procedimiento as p", "op.procedimiento_id", "=", "p.id_procedimiento");
        $query->leftJoin("rh_procedimiento_flujo as fl", "fl.id_flujo", "=", "op.flujo_id");
        $query->leftJoin("cat_sucursales as cs", "cs.id_sucursal", "=", "calidad_observacion.sucursal_id");
        $query->select(
            "calidad_plan_trabajo_observacion.*",
            'calidad_observacion.*', 'cn.descripcion as nombre_nivel',
            "f.descripcion as descripcion_funcion", "f.codigo as codigo_funcion","f.id_funcion as funcion_id",
            "p.nombre as nombre_procedimiento", "p.codigo as codigo_procedimiento", "fl.descripcion as flujo_descripcion","op.flujo_id",
            "cs.nombre as sucursal",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as nombre_responsable"),
            \DB::raw("concat(e2.nombre,' ',e2.apaterno,' ',e2.amaterno) as empleado_captura")


        );
        if(isset($datos["complemento_id"]))
            $query->where("calidad_plan_trabajo_observacion.complemento_id","=",$datos["complemento_id"]);

        return $query->get();
    }
}
