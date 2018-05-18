<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;

class  EntregablesTareas extends Model
{
    protected $table = 'proyectos_tareas_entregables';
    protected $fillable = ['tarea_id','ruta','creador_id','subtarea_id', 'filename', 'updated_at', 'created_at'];

    public function tareasAsignadas(){
        return $this->belongsTo('App\Model\Proyectos\TareasAsignadas', 'proyectos_tareas_asignadas','tarea_id','id');
    }

    public function buscar($datos){
        $query = $this->leftJoin("proyectos_tareas_asignadas as t","t.id", "=", "proyectos_tareas_entregables.tarea_id");
        $query->leftJoin('usuarios as ur', 'ur.id_usuario', '=', 'proyectos_tareas_entregables.creador_id');
        $query->leftJoin("proyectos_etapas as e", "e.id", "=", "t.etapa_id");
        $query->leftJoin('proyectos_tareas as ta', 'ta.id', '=', 't.tarea_id');

        //$query = $this->leftJoin('proyectos_subtareas as st', 'st.id', '=', 'proyectos_tareas_entregables.subtarea_id');
        $query->leftJoin("rh_empleados as e1", "e1.id_empleado", "=", "ur.id_empleado");

        $query->select(
            'ta.titulo as tarea',
            'proyectos_tareas_entregables.*',
            \DB::raw('CONCAT(e1.nombre, " ", e1.apaterno, " ", e1.amaterno) as nombre')
        );
        if(!empty($datos['subtarea_id'])){
            $query->where("proyectos_tareas_entregables.tarea_id", $datos['tarea_id']);
            $query->where("proyectos_tareas_entregables.subtarea_id", $datos['subtarea_id']);
        }
        else if(!empty($datos['tarea_id'])){
            $query->where("proyectos_tareas_entregables.tarea_id", $datos['tarea_id']);
            $query->whereNull("subtarea_id");
        }

        if(!empty($datos['id_tarea'])){
            $query->where("proyectos_tareas_entregables.tarea_id", $datos['id_tarea']);
        }
        if(!empty($datos['etapa_id'])){
            $query->where("e.id", $datos['etapa_id']);
        }


        return $query->get();
    }
}
