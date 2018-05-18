<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class funcionesPerfil extends Model
{
    //
    protected $table = "rh_funciones";
    protected $primaryKey = "id_funcion";
    public function funcionesPorPuesto($datos){
        $query = $this->leftJoin("rh_puesto_perfil as pp", "pp.id_puesto_perfil","=","rh_funciones.id_perfil");

        if(isset($datos["estatus"])){
            $query->where("pp.estatus","=",$datos["estatus"]);
        }
        if(isset($datos["puesto_id"])){
            $query->where("pp.id_puesto","=",$datos["puesto_id"]);
        }

        return $query->get();
    }
}
