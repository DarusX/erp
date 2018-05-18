<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class proceso extends Model
{
    //
    protected $table = "rh_procesos";

    public function buscar($datos)
    {

        $query = $this->leftJoin("rh_procesos_categoria as c", "c.id_proceso_categoria", "=", "rh_procesos.id_proceso_categoria");
        $query->leftJoin("rh_puestos_procesos as pp", function ($join) use ($datos) {
            $join->on("pp.id_proceso", "=", "rh_procesos.id_proceso")
                ->where("pp.id_puesto", "=", $datos["id_puesto"])
                ->where("pp.estatus","=","activo")
            ;
        });


        $query->select(
            "rh_procesos.*",
            "c.categoria",
            \DB::raw("ifnull(pp.id_proceso_puesto,0) as id_proceso_puesto"),
            "porcentaje"


        );
        if(isset($datos["id_perfil"]))
            $query->where("pp.id_puesto_perfil","=",$datos["id_perfil"]);

        return $query->get();

    }

}
