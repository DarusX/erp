<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaPagosMantenimiento extends Model
{
    protected $table = 'pagos_mantenimientos_inmobiliaria';

    public function buscar($datos)
    {
        $query = $this->select(
            'pagos_mantenimientos_inmobiliaria.*',
            'ii.nombre AS inmueble',
            'cii.nombre AS cliente'
        )
            ->leftJoin('inmuebles_inmobiliaria AS ii', 'ii.id', '=', 'pagos_mantenimientos_inmobiliaria.inmueble_id')
            ->leftJoin('catalogos_clientes_inmobiliaria AS cii', 'cii.id', '=', 'pagos_mantenimientos_inmobiliaria.cliente_id');
        if (!empty($datos['pago'])) {
            $query = $query->where('id', $datos['pago']);
        }
        return $query->get();
    }
}
