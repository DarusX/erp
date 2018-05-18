<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class puestoProceso extends Model
{
    //
    protected $table = "rh_puestos_procesos";
    protected $primaryKey = "id_proceso_puesto";
    protected $fillable = [
        'id_puesto_perfil',
        'id_proceso',
        'id_puesto',
        'porcentaje',
        'estatus'

    ];
    public function buscar($datos){
        $query = $this->leftJoin("rh_procesos as p","rh_puestos_procesos.id_proceso","=","p.id_proceso");
        $query->leftJoin("rh_procesos_categoria as c", "c.id_proceso_categoria", "=", "p.id_proceso_categoria");
        $query->select(
            "p.*",
            "c.categoria",
            \DB::raw("ifnull(rh_puestos_procesos.id_proceso_puesto,0) as id_proceso_puesto"),
            "porcentaje"


        );
        if(isset($datos["id_perfil"]))
            $query->where("rh_puestos_procesos.id_puesto_perfil","=",$datos["id_perfil"]);

        return $query->get();
    }
}
