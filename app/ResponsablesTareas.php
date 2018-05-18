<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResponsablesTareas extends Model
{
    public $table = 'proyectos_tareas_responsables';
    public $fillable = ['id','usuario_id','tarea_id', 'created_at','updated_at',];

    public function buscar($datos){
        $query = $this->leftJoin("proyectos_tareas_asignadas as t", "t.id", "=", "proyectos_tareas_responsables.tarea_id");
        $query->leftJoin("usuarios as u", "u.id_usuario", "=", "proyectos_tareas_responsables.usuario_id");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "u.id_empleado");
        $query->select(
            "u.id_usuario",
            \DB::raw('CONCAT(e.nombre, " ", e.apaterno, " ", e.amaterno) as nombre_empleado')
        );
        if (!empty($datos['tarea_id'])){
            $query->where("proyectos_tareas_responsables.tarea_id", $datos['tarea_id']);
        }
        return $query->get();
    }

public function buscarResponsables($datos){
    $query = $this->leftJoin("proyectos_tareas_asignadas as t", "t.id", "=", "proyectos_tareas_responsables.tarea_id");
    $query->leftJoin("usuarios as u", "u.id_usuario", "=", "proyectos_tareas_responsables.usuario_id");
    $query->select(
        \DB::raw("u.id_usuario")
    );
    if (!empty($datos['id'])){
        $query->where("proyectos_tareas_responsables.tarea_id", $datos['id']);
    }
    return $query->get();
}
}


