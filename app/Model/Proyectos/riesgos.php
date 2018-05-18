<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;

class riesgos extends Model
{
    protected $table = 'proyectos_riesgos';
    protected $fillable = ['nombre', 'nivel','descripcion','plan_control','creador_id'];

    public function buscar($datos){
        $query = $this->select(
          'proyectos_riesgos.*'
        );
        if(!empty($datos['tipo'])){
            $query->where("proyectos_riesgos.tipo", $datos['tipo']);
        }
        if(!empty($datos['nivel'])){
            $query->where("proyectos_riesgos.nivel", $datos['nivel']);
        }
        if(!empty($datos['riesgo'])){
            $query->where("proyectos_riesgos.id", $datos['riesgo']);
        }

        $query->groupBy("proyectos_riesgos.id");
        return $query->get();
    }
}
