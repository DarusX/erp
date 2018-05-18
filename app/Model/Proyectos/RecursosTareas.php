<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;

class RecursosTareas extends Model
{
    protected $table = 'proyectos_tareas_recursos';
    protected $fillable = ['codigo_descripcion','nombre','cantidad', 'costo','impuesto','descripcion','tipo','tarea_id','importe', 'producto_id','created_at','updated_at', 'sumatoriaIva','sumatoria','iva'];
    public function tarea(){
        return $this->hasMany('App\Model\Proyectos\tareas');
    }
    public function buscar($datos){
        $query = $this->leftJoin("proyectos_tareas as t", "t.id", "=", "proyectos_tareas_recursos.tarea_id");
        $query->leftJoin("proyectos_tareas_asignadas as ta", "ta.tarea_id", "=", "t.id");
        $query->leftJoin("proyectos_etapas as e", "e.id", "=", "ta.etapa_id");
        $query->select(
          'proyectos_tareas_recursos.*',
            't.titulo as tarea',
            \DB::raw('2 as bandera')
        );
        if(!empty($datos['tarea_id'])){
            $query->where("proyectos_tareas_recursos.tarea_id", $datos['tarea_id']);
        }

        if(!empty($datos['producto'])){
            $query->where("proyectos_tareas_recursos.tipo", $datos['producto']);
        }

        if(!empty($datos['recurso'])){
            $query->where("proyectos_tareas_recursos.tipo", "!=", "Productos");
        }
        /*if(!empty($datos['etapa_id'])){
            $query->where("proyectos_tareas_recursos.tarea_id", $datos['etapa_id']);
        }*/
        $query->groupBy('id');

        return $query->get();
    }
}
