<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PlanTrabajo extends Model
{
    //
    protected $table = "calidad_plan_trabajo";
    protected $fillable = array(
       'empleado_id',
       'sucursal_id',
        'empleado_captura_id',
        'fecha',
        'descripcion',
        'tipo',
        'titulo',
        'fecha_realizacion',
        'fecha_validacion',
        'cumplido',
        'estatus'

    );
    public function buscar($datos){

        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado", "=", "calidad_plan_trabajo.empleado_id");
        $query->leftJoin("rh_foto_empleado as f1", "f1.id_empleado", "=", "e.id_empleado");
        $query->leftJoin("rh_empleados as e2", "e2.id_empleado", "=", "calidad_plan_trabajo.empleado_captura_id");
        $query->leftJoin("rh_foto_empleado as f2", "f2.id_empleado", "=", "e2.id_empleado");
        $query->leftJoin("calidad_plan_trabajo_funcion as ef", "ef.plan_trabajo_id", "=", "calidad_plan_trabajo.id");
        $query->leftJoin("rh_funciones as f", "f.id_funcion", "=", "ef.funcion_id");
        $query->leftJoin("calidad_plan_trabajo_procedimiento as op", "op.plan_trabajo_id", "=", "calidad_plan_trabajo.id");
        $query->leftJoin("rh_procedimiento as p", "op.procedimiento_id", "=", "p.id_procedimiento");
        $query->leftJoin("rh_procedimiento_flujo as fl", "fl.id_flujo", "=", "op.flujo_id");
        $query->leftJoin("cat_sucursales as cs", "cs.id_sucursal", "=", "calidad_plan_trabajo.sucursal_id");


        $query->select(
            "calidad_plan_trabajo.*",
            "f.descripcion as descripcion_funcion", "f.codigo as codigo_funcion","f.id_funcion as funcion_id",
            "p.nombre as nombre_procedimiento", "p.codigo as codigo_procedimiento","op.procedimiento_id", "fl.descripcion as flujo_descripcion","op.flujo_id",
            "cs.nombre as sucursal",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as nombre_responsable"),"e.email_empresa as email_responsable",
            \DB::raw("concat(e2.nombre,' ',e2.apaterno,' ',e2.amaterno) as empleado_captura"), "e2.email_empresa as email_captura",
            "f1.id_foto_empleado as empleado_responsable_foto_id",
            "f2.id_foto_empleado as empleado_captura_foto_id"
        );
        if(isset($datos["fecha_inicio"]))
            $query->where("calidad_plan_trabajo.fecha",">=",$datos["fecha_inicio"]);
        if(isset($datos["empleado_id"]))
            $query->where("calidad_plan_trabajo.empleado_id","=",$datos["empleado_id"]);
        if(isset($datos['estatus_pendiente'])){
            $query->whereNotIn("calidad_plan_trabajo.estatus",["validado","cancelado"]);
        }



        if(isset($datos["id"]))
            return $query->where("calidad_plan_trabajo.id","=",$datos["id"])->first();
        else
            return $query->get();

    }
}
