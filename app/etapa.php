<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class etapa extends Model
{   //Declaración de variablas de la tabla de base de datos
    protected $table='proyectos_etapas';
    protected $fillable=['titulo','ponderacion','created_at','updated_at','creador_id','edit_id','avance','proyecto_id','dias_etapas'];

    //Métodos para definir la relación con otras tablas
    public function proyectos(){
        return $this->belongsToMany('App\proyecto', 'proyectos_proyectos');
    }
    public function tareas(){
        return $this->hasMany('App\tareas', 'proyectos_tareas');
    }
    public function riesgos(){
        return $this->hasMany('App\riesgos', 'proyectos_riesgos');
    }
    public function tareasAsignadas(){
        return $this->belongsToMany('App\TareasAsignadas', 'proyectos_tareas_asignadas', 'etapa_id','id');
    }
    //Método para retomar los datos de la tabla.
    public function buscar($datos){
        $query = $this->leftJoin("proyectos_proyectos as p", "p.id", "=", "proyectos_etapas.proyecto_id");

        $query->select(
            'proyectos_etapas.*',
            'p.nombre as proyecto'
        );
        if(!empty($datos['proyecto'])){
            $query->where("p.id", $datos['proyecto']);
        }
        if(!empty($datos['etapas'])){
            $query->where("proyectos_etapas.id", $datos['etapas']);
        }

        return $query->get();
    }

}
