<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaCatalogosTiposInmueble extends Model
{
    protected $table = 'catalogos_tipos_inmuebles_inmobiliaria';

    public function buscar($datos)
    {
        $query = $this;

        return $query->get();
    }
}