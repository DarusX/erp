<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductosRotacionesVentasDetalle extends Model
{
    public function buscarRotacionProductos($datos)
    {
        $query = $this->from('productos_rotaciones_ventas_detalles AS prvd')
            ->select(
                'prvd.*',
                'p.codigo_producto',
                'p.descripcion',
                'f.familia',
                'l.linea'
            )
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'prvd.producto_id')
            ->leftJoin('productos_familias AS f', 'f.id_familia', '=', 'p.id_familia')
            ->leftJoin('productos_lineas AS l', 'l.id_linea', '=', 'p.id_linea')
            ->where('producto_rotacion_venta_id', $datos['producto_rotacion_venta_id'])
            ->where('rotacion', $datos['rotacion'])
            ->orderBy('monto_ventas', 'DESC');

        return $query->get();
    }
}
