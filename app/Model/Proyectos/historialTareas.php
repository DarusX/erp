<?php
/**
 * Created by PhpStorm.
 * User: SISTEMAS
 * Date: 03/08/2017
 * Time: 07:38 AM
 */

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;

class historialTareas extends Model
{
    protected $table = 'proyectos_historial_tareas';
    protected $fillable = ['retroalimentacion', 'tarea_id', 'created_at', 'updated_at'];

    public function tareas(){

        return $this->belongsToMany('App\Model\Proyectos\TareasAsignadas', 'proyectods_tareas_asignadas');

    }

    public function buscar($datos){

        $query = $this->leftjoin("proyectos_tareas_asignadas as t", "t.id", "=", "proyectos_historial_tareas.tarea_id");

        $query->select(
            'proyectos_historial_tareas.*'
        );

        if(!empty($datos['tarea_id'])){
            $query->where("proyectos_historial_tareas.tarea_id", $datos['tarea_id']);
        }

        return $query->get();
    }
}