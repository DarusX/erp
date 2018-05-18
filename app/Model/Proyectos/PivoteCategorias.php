<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;

class PivoteCategorias extends Model
{
    protected $table = 'proyectos_pivote_categorias';
    protected $fillable = ['categoria_id', 'proyecto_id', 'created_at', 'updated_at'];

    public function buscar($datos){
        $query = $this->leftJoin('proyectos_proyectos as p', 'p.id', '=', 'proyectos_pivote_categorias.proyecto_id');
        $query->leftJoin('proyectos_categorias as c', 'c.id', '=', 'proyectos_pivote_categorias.categoria_id');

        $query->select(
          'proyectos_pivote_categorias.*',
          'c.nombre'
        );

        if(!empty($datos['proyecto_id'])){
            $query->where('proyectos_pivote_categorias.proyecto_id', $datos['proyecto_id']);
        }
        if(!empty($datos['categoria'])){
            $query->where('proyectos_pivote_categorias.categoria_id', $datos['categoria']);
        }

        return $query->get();
    }
}
