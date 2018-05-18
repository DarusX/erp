<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductosRotacionesVentasSucursal extends Model
{
    protected $table = 'productos_rotaciones_ventas_sucursales';

    public function buscarRotacionSucursal($datos)
    {
        $query = $this->select(
            '*'
        );

        if (isset($datos['sucursal_id'])) {
            $query = $query->where('sucursal_id', $datos['sucursal_id']);
        }

        return $query->orderBy('id', 'DESC')
            ->get();
    }
}