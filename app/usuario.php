<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class usuario extends Model
{

    public function proyecto(){
        return $this->belongsTo('App\proyecto','lider_id', 'id_usuario');
    }
    public function proyecto2(){
        return $this->belongsTo('App\proyecto', 'admin_id', 'id_usuario');
    }
    public function proyecto3(){
    return $this -> belongsToMany('App\proyecto','proyectos_observadores','usuario_id','proyecto_id');
    }
    public function proyectos(){
        return $this->belongsToMany('App\proyecto','proyectos_observadores');
    }
    public function tareas(){
        return $this->belongsToMany('App\TareasAsignadas', 'proyectos_tareas_responsables');
}

}
