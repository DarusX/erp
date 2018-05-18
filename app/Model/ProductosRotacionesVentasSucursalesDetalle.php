<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductosRotacionesVentasSucursalesDetalle extends Model
{
    public function buscarRotacionSucursalProductos($datos)
    {
        $query = $this->from('productos_rotaciones_ventas_sucursales_detalles AS prvsd')
            ->select(
                'prvsd.*',
                'a.almacen',
                'p.codigo_producto',
                'p.descripcion'
            )
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'prvsd.producto_id')
            ->leftJoin('almacenes AS a', 'a.id_almacen', '=', 'prvsd.almacen_id')
            ->where('producto_rotacion_venta_sucursal_id', $datos['producto_rotacion_venta_sucursal_id'])
            ->where('rotacion', $datos['rotacion'])
            ->orderBy('monto_ventas', 'DESC');

        return $query->get();
    }
}