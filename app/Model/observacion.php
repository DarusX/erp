<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class observacion extends Model
{
    //
    protected $table = "calidad_observacion";
    protected $fillable = array(
        'empleado_responsable_id',
        'empleado_captura_id',
        'tipo',
        'sucursal_id',
        'fecha_captura',
        'fecha_aceptacion',
        'fecha_compromiso',
        'fecha_finalizacion',
        'fecha_auditoria',
        'empleado_auditor_id',
        'nivel_id',
        'estatus',
        'cumplida'
    );

    public function buscar($datos)
    {
        $query = $this->leftJoin("calidad_observacion_nivel as cn", "cn.id", "=", "calidad_observacion.nivel_id");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "calidad_observacion.empleado_responsable_id");
        $query->leftJoin("rh_empleados as e2", "e2.id_empleado", "=", "calidad_observacion.empleado_captura_id");
        $query->leftJoin("calidad_observacion_funcion as ef", "ef.observacion_id", "=", "calidad_observacion.id");
        $query->leftJoin("rh_funciones as f", "f.id_funcion", "=", "ef.funcion_id");
        $query->leftJoin("calidad_observacion_procedimiento as op", "op.observacion_id", "=", "calidad_observacion.id");
        $query->leftJoin("rh_procedimiento as p", "op.procedimiento_id", "=", "p.id_procedimiento");
        $query->leftJoin("rh_procedimiento_flujo as fl", "fl.id_flujo", "=", "op.flujo_id");
        $query->leftJoin("cat_sucursales as cs", "cs.id_sucursal", "=", "calidad_observacion.sucursal_id");

        if (isset($datos["finalizada_auditoria"])) {
            if ($datos["finalizada_auditoria"] != "")
                $query->where("calidad_observacion.estatus", "!=", $datos["finalizada_auditoria"]);
        }if (isset($datos["sucursal_id"])) {
            if ($datos["sucursal_id"] != "")
                $query->where("calidad_observacion.sucursal_id", "!=", $datos["sucursal_id"]);
        }
        if (isset($datos["estatus"])) {
            if ($datos["estatus"] != "")
                $query->where("calidad_observacion.estatus", "=", $datos["estatus"]);
        }
        if (isset($datos["tipo"])) {
            if ($datos["tipo"] != "")
                $query->where("calidad_observacion.tipo", "=", $datos["tipo"]);
        }
        if (isset($datos["nivel_id"])) {
            if ($datos["nivel_id"] != "")

                $query->where("calidad_observacion.nivel_id", "=", $datos["nivel_id"]);
        }
        if (isset($datos["funcion_id"])) {
            if ($datos["funcion_id"] != "")
                $query->where("ef.funcion_id", "=", $datos["funcion_id"]);
        }
        if (isset($datos["proceso_id"])) {
            if ($datos["proceso_id"] != "")
                $query->where("op.procedimiento_id", "=", $datos["proceso_id"]);
        }
        if (isset($datos["flujo_id"])) {
            if ($datos["flujo_id"] != "")
                $query->where("op.flujo_id", "=", $datos["flujo_id"]);
        }
        if (isset($datos["empleado_responsable_id"])) {
            if ($datos["empleado_responsable_id"] != "")
                $query->where("e.id_empleado", "=", $datos["empleado_responsable_id"]);
        }


        $query->select(
            'calidad_observacion.*', 'cn.descripcion as nombre_nivel',
            "f.descripcion as descripcion_funcion", "f.codigo as codigo_funcion","f.id_funcion as funcion_id","op.procedimiento_id",
            "p.nombre as nombre_procedimiento", "p.codigo as codigo_procedimiento", "fl.descripcion as flujo_descripcion","op.flujo_id",
            "cs.nombre as sucursal",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as nombre_responsable"),
            \DB::raw("concat(e2.nombre,' ',e2.apaterno,' ',e2.amaterno) as empleado_captura")


        );

        if (isset($datos["id"])) {
//            dd($query->toSql());
            $query->where("calidad_observacion.id", "=", $datos["id"]);
            return $query->first()->toArray();
        }
        return $query->get();

    }
}
