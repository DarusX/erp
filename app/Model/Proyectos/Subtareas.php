<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;

class Subtareas extends Model
{
    protected $table = "proyectos_subtareas";
    protected $fillable = ['titulo', 'descripcion', 'etiqueta', 'creador_id', 'edit_id', 'created_at', 'updated_at'];

    public function buscar($datos){
        $query = $this->select(
            'proyectos_subtareas.*'
        );

        if(!empty($datos['subtarea'])){
            $query->where("proyectos_subtareas.id", $datos['subtarea']);
        }

        if(!empty($datos['etiqueta'])){
            $query->where("proyectos_subtareas.etiqueta", "like", '%' . $datos['etiqueta'] . '%');
        }


        return $query->get();
    }
}
