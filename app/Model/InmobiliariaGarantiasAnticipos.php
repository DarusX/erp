<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaGarantiasAnticipos extends Model
{
    protected $table = 'garantias_anticipos_inmobiliaria';

    public function buscar($datos)
    {
        $query = $this->select(
            'garantias_anticipos_inmobiliaria.*',
            'ii.nombre AS inmueble',
            'cci.nombre AS cliente'
        )
            ->leftJoin('garantias_inmobiliaria AS gi', 'gi.id', '=', 'garantias_anticipos_inmobiliaria.garantia_id')
            ->leftJoin('inmuebles_inmobiliaria AS ii', 'ii.id', '=', 'gi.inmueble_id')
            ->leftJoin('catalogos_clientes_inmobiliaria AS cci', 'cci.id', '=', 'gi.cliente_id');

        if (!empty($datos['anticipo'])) {
            $query = $query->where('id', $datos['anticipo']);
        }

        return $query->get();
    }
}