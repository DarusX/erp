<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class procedimiento extends Model
{
    //
    protected $table = "calidad_procedimiento";
    protected $fillable = [
        'procedimiento',
        'codigo',
        'empleado_captura_id',
        'empleado_valida_id',
        'empleado_cancela_id',
        'fecha_validacion',
        'fecha_cancelacion',
        'estatus'
    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado", "=", "calidad_procedimiento.empleado_captura_id");
        $query->leftJoin("rh_empleados as e2", "e2.id_empleado", "=", "calidad_procedimiento.empleado_valida_id");
        $query->leftJoin("rh_empleados as e3", "e3.id_empleado", "=", "calidad_procedimiento.empleado_cancela_id");
        $query->leftJoin("calidad_categoria_procedimiento as ccp2", "ccp2.procedimiento_id", "=", "calidad_procedimiento.id");


        $query->select(
            "calidad_procedimiento.*",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as empleado_captura"),
            \DB::raw("concat(e2.nombre,' ',e2.apaterno,' ',e2.amaterno) as empleado_valida"),
            \DB::raw("concat(e3.nombre,' ',e3.apaterno,' ',e3.amaterno) as empleado_cancela"),
            \DB::raw("'NO' as asignado")

        );
        if (!empty($datos["procedimiento"])) {
            $query->where("calidad_procedimiento.procedimiento", "like", "%" . $datos["procedimiento"] . "%");
        }
        if (!empty($datos["codigo"])) {
            $query->where("calidad_procedimiento.codigo", "like", "%" . $datos["codigo"] . "%");
        }
        if (!empty($datos["session"])) {
            $query->leftJoin("calidad_categoria_procedimiento as ccp", "ccp.procedimiento_id", "=", "calidad_procedimiento.id");

            $query->whereNull("ccp.id");
        }
        if (!empty($datos["categoria_id"])) {
            $query->where("ccp2.categoria_id", "=", $datos["categoria_id"]);

        }
        if (!empty($datos["estatus"])) {
            $query->where("calidad_procedimiento.estatus", "=", $datos["estatus"]);
        }
        //dd($query->toSql());
        if (!empty($datos["id"])) {
            return $query->where("calidad_procedimiento.id", "=", $datos["id"])->first();

        } else {
            return $query->get();

        }

    }

}
