<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tareas extends Model
{
    protected $table = 'proyectos_tareas';
    protected $fillable = ['titulo', 'descripcion', 'dias_ejec', 'ponderacion', 'avance', 'etapa_id', 'etiqueta', 'creador_id', 'created_at', 'updated_at'];

    public function responsables(){
        return $this->belongsToMany('App\usuario', 'proyectos_tareas_responsables', 'tarea_id','usuario_id');
    }
    public function tareas_asignadas(){
        return $this->belongsToMany('App\TareasAsignadas', 'proyectos_tareas_asignadas', 'tarea_id', 'id');
    }

    public function buscar($datos)
    {
        $query = $this->select(
            'proyectos_tareas.*'
        );

        return $query->get();
    }

}
