<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;


class etapa extends Model
{   //Declaración de variablas de la tabla de base de datos
    protected $table='proyectos_etapas';
    protected $fillable=['titulo','clave','ponderacion','created_at','updated_at','creador_id','edit_id','avance','proyecto_id','dias_etapas','orden','porcentaje', 'dias_desfase', 'fecha_inicio','fecha_final'];

    //Métodos para definir la relación con otras tablas

    public function proyectos(){
        return $this->belongsToMany('App\Model\Proyectos\proyecto', 'proyectos_proyectos');
    }
    /*public function tareas(){
        return $this->hasMany('App\tareas', 'proyectos_tareas');
    }*/
    public function riesgos(){
        return $this->belongsToMany('App\Model\Proyectos\riesgos_pivote', 'proyectos_riesgos_pivote', 'etapa_id','riesgo_id');
    }
    public function tareasAsignadas(){
        return $this->belongsToMany('App\Model\Proyectos\tareas', 'proyectos_tareas_asignadas', 'etapa_id','id');
    }
    //Método para retomar los datos de la tabla.
    public function buscar($datos){
        $query = $this->leftJoin("proyectos_proyectos as p", "p.id", "=", "proyectos_etapas.proyecto_id");

        $query->select(
            'proyectos_etapas.*',
            'p.nombre as proyecto',
            'p.estado as estatus',
            'p.lider_id as lider_id'
        );
        if(!empty($datos['proyecto'])){
            $query->where("p.id", $datos['proyecto']);
        }
        if(!empty($datos['etapas'])){
            $query->where("proyectos_etapas.id", $datos['etapas']);
        }
        if(!empty($datos['proyecto_id'])){
            $query->where("p.id", $datos['proyecto_id']);
        }
        $query->orderBy('created_at');

        return $query->get();
    }
}
