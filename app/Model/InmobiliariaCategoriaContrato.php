<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaCategoriaContrato extends Model
{
    protected $table = 'categorias_contratos_inmobiliaria';
    protected $fillable = ['nombre'];

    public function buscar($datos)
    {
        $query = $this;
        if (!empty($datos['nombre'])) {
            $query->where('nombre', 'LIKE', '%' . $datos['nombre'] . '%');
        }
        return $query->get();
    }
}