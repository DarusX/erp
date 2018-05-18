<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TareasAsignadas extends Model
{
    protected  $table= 'proyectos_tareas_asignadas';
    protected $fillable = ['etapa_id','ponderacion','avance','dias_ejec','tarea_id','created_at','updated_at', 'creador_id','edit_id'];

    public function tareas(){
        return $this->belongsToMany('App\tareas', 'proyectos_tareas', 'tarea_id', 'id');
    }
    public function etapas(){
        return $this->belongsToMany('App\etapa', 'proyectos_etapas', 'etapa_id', 'id');
    }
    public function responsables(){
        return $this->belongsToMany('App\usuario', 'proyectos_tareas_responsables', 'tarea_id','usuario_id');
    }

    public function buscar($datos){
        $query = $this->leftJoin("proyectos_tareas as t", "t.id", "=", "proyectos_tareas_asignadas.tarea_id");
        $query->leftJoin("proyectos_etapas as e", "e.id", "=", "proyectos_tareas_asignadas.etapa_id");

        $query->select(
            'proyectos_tareas_asignadas.*',
            't.titulo as tarea',
            't.descripcion as descripcion',
            'e.titulo as etapa',
            \DB::raw('2 as bandera')
        );
        if(!empty($datos['etapa_id'])){
            $query->where("proyectos_tareas_asignadas.etapa_id", $datos['etapa_id']);
        }
        $query->groupBy("proyectos_tareas_asignadas.id");
        return $query->get();
    }
}
