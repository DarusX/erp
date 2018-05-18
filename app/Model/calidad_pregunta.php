<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class calidad_pregunta extends Model
{
    //
    protected $table = "calidad_pregunta";
    protected $fillable = [
        'pregunta',
        'empleado_captura_id',
        'empleado_cancela_id',
        'estatus',

    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado", "=", "empleado_captura_id");
        $query->leftJoin("rh_empleados as e2", "e2.id_empleado", "=", "empleado_cancela_id");

        $query->select(
            "calidad_pregunta.*",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as empleado_captura"),
            \DB::raw("concat(e2.nombre,' ',e2.apaterno,' ',e2.amaterno) as empleado_cancela")

        );
        if (!empty($datos["pregunta"])) {
            $query->where("calidad_pregunta.pregunta", "like", "%" . $datos["pregunta"] . "%");
        }

        //return $query->toSql();

        if (!empty($datos["id"])) {
            $query->where("calidad_pregunta.id", "=", $datos["id"]);
            return $query->first();
        }

        return $query->get();
    }
}
