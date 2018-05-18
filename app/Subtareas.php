<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subtareas extends Model
{
    protected $table = "proyectos_subtareas";
    protected $fillable = ['titulo', 'descripcion', 'etiqueta', 'creador_id', 'edit_id', 'created_at', 'updated_at'];

    public function buscar($datos){
        $query = $this->select(
            'proyectos_subtareas.*'
        );

        return $query->get();
    }
}
