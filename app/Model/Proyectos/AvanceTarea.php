<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;

class AvanceTarea extends Model
{
    protected $table = 'proyectos_avance_tarea';
    protected $fillable = ['subtarea_id', 'tarea_id', 'estado', 'created_at', 'updated_at'];

    public function buscar($datos){
        $query = $this->leftJoin('proyectos_subtareas_asignadas as sa', 'sa.id', '=', 'proyectos_avance_tarea.subtarea_id');
        $query->leftJoin('proyectos_tareas_asignadas as t','t.id', '=', 'proyectos_avance_tarea.tarea_id');
        $query->leftJoin('proyectos_subtareas as s', 's.id', '=', 'sa.subtarea_id');
        $query->select(
          'proyectos_avance_tarea.*'
        );

        if(!empty($datos['tarea_id'])){
            $query->where('proyectos_avance_tarea.tarea_id', $datos['tarea_id']);
        }
        if(!empty($datos['subtarea_id'])){
            $query->where('proyectos_avance_tarea.subtarea_id', $datos['subtarea_id']);
        }

        return $query->get();
    }
}
