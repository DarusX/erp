<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class cprocedimientoActividadPuesto extends Model
{
    //
    protected $table = "procedimiento_actividad_puesto";
    protected $fillable = [
        'puesto_id',
        'perfil_id',
        'verbo_id',
        'actividad_id',
        'procedimiento_id',


    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("rh_puestos as p", "p.id_puesto", "=", "puesto_id");
        $query->leftJoin("calidad_verbo as v", "v.id", "=", "procedimiento_actividad_puesto.verbo_id");


        if (!empty($datos["actividad_id"])) {
            $query->where("actividad_id", "=", $datos["actividad_id"]);
        }
        if (!empty($datos["procedimiento_id"])) {
            $query->where("procedimiento_id", "=", $datos["procedimiento_id"]);
        }
        $query->select("*", "perfil_id as id_puesto_perfil", "actividad_id as key", "v.verbo");

        return $query->get();
    }
}
