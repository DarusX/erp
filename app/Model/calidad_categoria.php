<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class calidad_categoria extends Model
{
    //
    protected $table = "calidad_categoria";
    protected $fillable = [
        'categoria',
        'codigo',
        'empleado_captura_id',
        'empleado_valida_id',
        'fecha_validacion',
        'empleado_cancela_id',
        'fecha_cancelacion',
        'estatus',

    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado", "=", "calidad_categoria.empleado_captura_id");
        $query->leftJoin("rh_empleados as e2", "e2.id_empleado", "=", "calidad_categoria.empleado_valida_id");
        $query->leftJoin("rh_empleados as e3", "e3.id_empleado", "=", "calidad_categoria.empleado_cancela_id");

        $query->select(
            "calidad_categoria.*",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as empleado_captura"),
            \DB::raw("concat(e2.nombre,' ',e2.apaterno,' ',e2.amaterno) as empleado_valida"),
            \DB::raw("concat(e3.nombre,' ',e3.apaterno,' ',e3.amaterno) as empleado_cancela")

        );
        if (!empty($datos["categoria"])) {
            $query->where("calidad_categoria.categoria", "like", "%" . $datos["categoria"] . "%");
        }
        if (!empty($datos["estatus"])) {
            $query->where("calidad_categoria.estatus", "=", $datos["estatus"]);
        }
        if (!empty($datos["id"])) {
            $query->where("calidad_categoria.id", "=", $datos["id"]);
            return $query->first();
        }

        return $query->get();

    }
}
