<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaCatalogosMedioDifusion extends Model
{
    protected $table = 'catalogos_medios_difusion_inmobiliaria';
    protected $fillable = ['nombre'];

    public function buscar($datos)
    {
        $query = $this;

        if (!empty($datos['nombre'])) {
            $query = $query->where('nombre', 'LIKE', '%' . $datos['nombre'] . '%');
        }

        return $query->get();
    }
}