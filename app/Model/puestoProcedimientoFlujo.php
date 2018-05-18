<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class puestoProcedimientoFlujo extends Model
{
    //
    protected $table = "rh_procedimiento_flujo";
    public function buscar($datos){
        $query= $this->leftJoin("rh_procedimiento as p","p.id_procedimiento","=","rh_procedimiento_flujo.id_procedimiento");

        if($datos["id_procedimiento"])
        {
            $query->where('p.id_procedimiento','=',$datos["id_procedimiento"]);
        }

        return $query->get();
    }
}
