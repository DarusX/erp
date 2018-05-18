<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PlanTrabajoComplemento extends Model
{
    //
    protected $table = "calidad_plan_trabajo_complemento";
    protected $fillable = [
        'plan_trabajo_id',
        'empleado_captura_id',
        'comentario',
        'tipo'

    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado", "=", "calidad_plan_trabajo_complemento.empleado_captura_id");
        $query->leftJoin("rh_foto_empleado as f2", "f2.id_empleado", "=", "e.id_empleado");


        if (isset($datos["empleado_id"]))
            $query->where("empleado_captura_id", "=", $datos["empleado_id"]);
        if(isset($datos["plan_trabajo_id"]))
            $query->where("plan_trabajo_id","=",$datos["plan_trabajo_id"]);


        $query->select(
            "calidad_plan_trabajo_complemento.*",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as nombre_responsable"), "e.email_empresa as email_captura",
            "f2.id_foto_empleado as empleado_responsable_foto_id"


        );
        //dd($query->toSql());

        return $query->get();
    }

}
