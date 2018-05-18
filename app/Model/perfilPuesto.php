<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class perfilPuesto extends Model
{
    //
    protected $table = "calidad_perfil_bono";
    protected $fillable = [
        'perfil_id',
        'puesto_id',
        'bono_id',
        'estatus',
    ];
    public function buscar($datos){
        $query = $this->leftJoin("rh_bono as b","b.id_bono","=","calidad_perfil_bono.bono_id");

        if(!empty($datos["perfil_id"])){
            $query->where("perfil_id","=",$datos["perfil_id"]);
        }
        return $query->get();
    }
}
