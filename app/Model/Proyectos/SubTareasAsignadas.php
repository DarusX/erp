<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;

class SubTareasAsignadas extends Model
{
    protected $table= 'proyectos_subtareas_asignadas';
    protected $fillable = ['tarea_id', 'ponderacion', 'avance','dias_ejec','created_at','updated_at','creador_id','edit_id','subtarea_id','orden'];

    public function subtareas(){
        return $this->belongsToMany('App\Model\Proyectos\Subtareas', 'proyectos_subtareas', 'subtarea_id','id');
    }
    public function tareas(){
        return $this->belongsToMany('App\Model\Proyectos\tareas', 'proyectos_tareas', 'tarea_id', 'id');
    }
    public function buscar($datos){
        $query = $this->leftJoin("proyectos_subtareas as s", "s.id", "=", "proyectos_subtareas_asignadas.subtarea_id");
        $query->leftJoin("proyectos_tareas as t", "t.id", "=", "proyectos_subtareas_asignadas.tarea_id");

        $query->select(
            'proyectos_subtareas_asignadas.*',
            't.titulo as tarea',
            's.titulo as subtarea',
            \DB::raw('2 as bandera')
        );
        if(!empty($datos['tarea_id'])){
            $query->where("proyectos_subtareas_asignadas.tarea_id", $datos['tarea_id']);
        }
        $query->groupBy("proyectos_subtareas_asignadas.id");
        return $query->get();
    }
}
