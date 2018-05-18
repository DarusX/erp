<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaCatalogosCategoriaTarea extends Model
{
    protected $table = 'catalogos_categorias_tareas_inmobiliaria';
    protected $fillable = ['categoria', 'usuario_id'];

    public function buscar($datos)
    {
        $query = $this;
        if (!empty($datos['nombre'])) {
            $query->where('categoria', 'LIKE', '%' . $datos['nombre'] . '%');
        }
        return $query->get();
    }
}