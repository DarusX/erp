<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaCatalogosMaquinaria extends Model
{
    protected $table = 'catalogos_maquinaria_inmobiliaria';
    protected $fillable = ['nombre', 'costo'];

    public function buscar($datos)
    {
        $query = $this;
        if (!empty($datos['nombre'])) {
            $query = $query->where('nombre', 'LIKE', '%' . $datos['nombre'] . '%');
        }
        if (!empty($datos['costo'])) {
            $query = $query->where('costo', 'LIKE', '%' . $datos['costo'] . '%');
        }
        return $query->get();
    }
}
