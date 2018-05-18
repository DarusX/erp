<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;
use DB;

class TareasAsignadas extends Model
{
    protected  $table= 'proyectos_tareas_asignadas';
    protected $fillable = ['dias_desfase', 'retroalimentacion','clave','etapa_id','ponderacion','avance','dias_ejec','tarea_id','created_at','updated_at', 'creador_id','edit_id', 'orden', 'estatus', 'fecha_iniciacion', 'fecha_finalizacion', 'inicio_id', 'termino_id','fecha_inicio', 'fecha_final'];

    public function tareas(){
        return $this->belongsToMany('App\Model\Proyectos\tareas', 'proyectos_tareas', 'tarea_id', 'id');
    }
    public function etapas(){
        return $this->belongsToMany('App\Model\Proyectos\etapa', 'proyectos_etapas', 'etapa_id', 'id');
    }
    public function responsables(){
        return $this->belongsToMany('App\Model\Proyectos\usuario', 'proyectos_tareas_responsables', 'tarea_id','usuario_id');
    }
    public function riesgos(){
        return $this->belongsToMany('App\Model\Proyectos\riesgos_pivote', 'proyectos_riesgos_pivote', 'tarea_id','riesgo_id');
    }

    public function buscar($datos){
        $query = $this->leftJoin("proyectos_tareas as t", "t.id", "=", "proyectos_tareas_asignadas.tarea_id");
        $query->leftJoin("proyectos_tareas_recursos as tr", "tr.tarea_id", "=", "t.id");
        $query->leftJoin("proyectos_etapas as e", "e.id", "=", "proyectos_tareas_asignadas.etapa_id");
        $query->leftJoin("proyectos_proyectos as p", "p.id", "=", "e.proyecto_id");
        $query->leftJoin("proyectos_tareas_responsables as r", "r.tarea_id", "=", "proyectos_tareas_asignadas.id");
        $query->leftJoin("proyectos_equipos as pe", "pe.proyecto_id", "=", "e.proyecto_id");

        $query->select(
            'proyectos_tareas_asignadas.*',
            't.titulo as tarea',
            't.descripcion as descripcion',
            't.porcentaje',
            'e.titulo as etapa',
            't.titulo as title',
            'p.nombre as proyecto',
            'p.id as proyecto_id',
            'p.estado as proyecto_estado',
            'proyectos_tareas_asignadas.fecha_inicio as start',
            'proyectos_tareas_asignadas.fecha_final as end',
            \DB::raw('if(proyectos_tareas_asignadas.estatus = "Terminada", "#378006",if(proyectos_tareas_asignadas.estatus = "Rechazada", "#990000", if(proyectos_tareas_asignadas.estatus = "Validada", "#378006", if(proyectos_tareas_asignadas.estatus = "Proceso", "#DBB100", if(proyectos_tareas_asignadas.estatus = "Espera", "#3399FF", "#0489B1"))))) as color'),

            \DB::raw('2 as bandera')
        );
        if(!empty($datos['estatus'])){
            $query->where("proyectos_tareas_asignadas.estatus", $datos["estatus"]);
        }
        if(!empty($datos['autorizado'])){
            $query->where("p.estado", $datos["autorizado"]);
        }
        if(!empty($datos['etapa_id'])){
            //$query->where("proyectos_tareas_asignadas.etapa_id", $datos['etapa_id']);
            $query->where("e.id", $datos['etapa_id']);
        }
        if(!empty($datos['fecha_inicio'])){
            $query->where(DB::raw("DATE(proyectos_tareas_asignadas.fecha_inicio)"),'>=', $datos['fecha_inicio']);

        }
        if(!empty($datos['fecha_final'])){
            $query->where(DB::raw("DATE(proyectos_tareas_asignadas.fecha_final)"),'<=', $datos['fecha_final']);
        }
        if(!empty($datos['usuario_id'])){
            $query->where("r.usuario_id", $datos['usuario_id']);
        }
        if(!empty($datos['tarea_id'])){
            $query->where("proyectos_tareas_asignadas.id", $datos['tarea_id']);
        }
        if(!empty($datos['proyecto_id'])){
            $query->where("p.id", $datos['proyecto_id']);
        }


        $query->groupBy("proyectos_tareas_asignadas.id");

        $query->orderBy('orden');

        if(!empty($datos['orden_fecha'])){
            $query->orderBy('fecha_inicio');
        }

        if(!empty($datos["first"])){
            return $query->first();
        }

        //dd($query->toSql());
        return $query->get();


    }

}
