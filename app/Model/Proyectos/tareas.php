<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;

class tareas extends Model
{
    protected $table = 'proyectos_tareas';
    protected $fillable = ['titulo', 'descripcion', 'dias_ejec', 'ponderacion', 'avance', 'etapa_id', 'etiqueta', 'creador_id', 'created_at', 'updated_at','tarea'];

    public function responsables(){
        return $this->belongsToMany('App\Model\Proyectos\usuario', 'proyectos_tareas_responsables', 'tarea_id','usuario_id');
    }
    public function tareasAsignadas(){
        return $this->belongsToMany('App\Model\Proyectos\etapa', 'proyectos_tareas_asignadas', 'tarea_id', 'id');
    }
    public function riesgos(){
        return $this->belongsToMany('App\Model\Proyectos\riesgos_pivote', 'proyectos_riesgos_pivote', 'tarea_id','riesgo_id');
    }

    public function buscar($datos)
    {
        $query = $this->select(
            'proyectos_tareas.*'
        );

        if(!empty($datos['tarea'])){
            $query->where("proyectos_tareas.id", $datos['tarea']);
        }

        if(!empty($datos['etiqueta'])){
            $query->where("proyectos_tareas.etiqueta", "like", '%' . $datos['etiqueta'] . '%');
        }


        return $query->get();
    }

}
