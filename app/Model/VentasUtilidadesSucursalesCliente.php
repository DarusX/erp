<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VentasUtilidadesSucursalesCliente extends Model
{
    public function buscar($params)
    {
        $query = $this->from('ventas_utilidades_sucursales_clientes AS vusc')
            ->select(
                'vusc.*'
            )
            ->where('tipo_precio_id', $params['tipo_precio_id'])
            ->where('sucursal_id', $params['sucursal_id']);

        if (!empty($params['first']) && $params['first']) {
            return $query->first();
        }

        return $query->get();
    }
}