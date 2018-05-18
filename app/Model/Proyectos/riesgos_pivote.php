<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;

class riesgos_pivote extends Model
{
    protected $table ='proyectos_riesgos_pivote';
    protected $fillable = ['riesgo_id', 'proyecto_id','etapa_id','tarea_id'];

    public function buscar($datos){
        $query = $this->leftJoin("proyectos_proyectos as p", "p.id", "=", "proyectos_riesgos_pivote.proyecto_id");
        $query ->leftJoin("proyectos_etapas as e", "e.id", "=", "proyectos_riesgos_pivote.etapa_id");
        $query ->leftJoin("proyectos_tareas_asignadas as t", "t.id", "=", "proyectos_riesgos_pivote.tarea_id");
        $query ->leftJoin("proyectos_riesgos as r", "r.id", "=", "proyectos_riesgos_pivote.riesgo_id");

        $query->select(
            "proyectos_riesgos_pivote.*",
            "r.nombre as riesgo",
        "r.descripcion as descripcion",
        "r.nivel as nivel",
        "r.plan_control as plan_control",
            "e.titulo as etapa"
        );
        if(!empty($datos['etapa_id'])){
            $query->where("e.id", $datos['etapa_id']);
        }
        if(!empty($datos['proyecto_id'])){
            $query->where("p.id", $datos['proyecto_id']);
        }
        if(!empty($datos['tarea_id'])){
            $query->where("t.id", $datos['tarea_id']);
        }

        return $query->get();
    }
}
