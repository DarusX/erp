<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class observadore extends Model
{
    protected $table= 'proyectos_observadores';
    protected $fillable = ['usuario_id', 'proyecto_id'];

    public function buscar($datos){
        $query = $this->leftJoin("proyectos_proyectos as p", "p.id", "=", "proyectos_observadores.proyecto_id");
        $query->leftJoin("usuarios as u", "u.id_usuario", "=", "proyectos_observadores.usuario_id");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "u.id_empleado");
        $query->select(
            \DB::raw('CONCAT(e.nombre, " ", e.apaterno, " ", e.amaterno) as nombre_empleado')
        );
        if (!empty($datos['proyecto_id'])){
            $query->where("proyectos_observadores.proyecto_id", $datos['proyecto_id']);
        }
        return $query->get();
    }
    //
    /*public function proyecto(){
        return $this->hasMany('App\proyecto');
    }*/
}
