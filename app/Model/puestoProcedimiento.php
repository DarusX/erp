<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class puestoProcedimiento extends Model
{
    //
    protected $table = "rh_puesto_procedimiento";
    public function buscar($datos){
        $query = $this->leftJoin("rh_procedimiento as p","p.id_procedimiento","=","rh_puesto_procedimiento.id_procedimiento");
        $query->leftJoin("rh_puestos as p2","p2.id_puesto","=","rh_puesto_procedimiento.id_puesto");
        $query->select(
            "rh_puesto_procedimiento.*",
            "p.*"
        );
        if(isset($datos["estatus"])){
            $query->where("p.estatus","=",$datos["estatus"]);
        }
        if(isset($datos["id_puesto"])){
            $query->where("rh_puesto_procedimiento.id_puesto","=",$datos["id_puesto"]);
        }
        return $query->get();

    }
}
