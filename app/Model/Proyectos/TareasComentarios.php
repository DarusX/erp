<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;

class TareasComentarios extends Model
{
    protected $table = 'proyectos_tareas_comentarios';
    protected $fillable = ['tarea_id', 'comentario', 'creador_id','subtarea_id' ,'created_at', 'updated_at'];

    public function tareas()
    {
        return $this->belongsToMany('App\Model\Proyectos\TareasAsignadas', 'proyectos_tareas_asignadas');
    }

    public function buscar($datos)
    {
        $query = $this->leftJoin("proyectos_tareas_asignadas as t", "t.id", "=", "proyectos_tareas_comentarios.tarea_id");
        $query->leftJoin("usuarios as u", "u.id_usuario", "=", "proyectos_tareas_comentarios.creador_id");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "u.id_empleado");
        $query->leftJoin("proyectos_tareas as pt", "pt.id", "=", "t.tarea_id");

        $query->select(
            'proyectos_tareas_comentarios.*',
            'pt.titulo as tarea',
            \DB::raw('CONCAT(e.nombre, " ", e.apaterno, " ", e.amaterno) as nombre')
        );
        if(!empty($datos['usuario_id'])){
            $query->where("proyectos_tareas_comentarios.creador_id", $datos['usuario_id']);
        }
        if(!empty($datos['subtarea_id'])){
            $query->where("proyectos_tareas_comentarios.tarea_id", $datos['tarea_id']);
            $query->where("proyectos_tareas_comentarios.subtarea_id", $datos['subtarea_id']);
        }
        else if(!empty($datos['tarea_id'])){
            $query->where("proyectos_tareas_comentarios.tarea_id", $datos['tarea_id']);
            $query->whereNull("subtarea_id");
        }


        return $query->get();
    }
}
