<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PagosProveedoresDescripcion extends Model
{
    protected $table = "compras_ordenes_facturas_pagos_proveedores_descripcion";

    protected $primaryKey = "id_proveedor_pago_factura";
    public $timestamps = false;

    public function obtenerFacturas($datos)
    {
        $query = $this->leftJoin('compras_ordenes_facturas', 'compras_ordenes_facturas_pagos_proveedores_descripcion.id_orden_factura', '=', 'compras_ordenes_facturas.id_orden_factura');

        $select = ['compras_ordenes_facturas.factura', 'compras_ordenes_facturas.importe', 'compras_ordenes_facturas.subtotal', 'compras_ordenes_facturas.iva',
            \DB::raw("obtenerCorreoUsuario(compras_ordenes_facturas.id_usuario) as emailUsuario"),
        ];

        $query = $query->select($select);
        if ($datos["id_proveedor_pago"]) {
            $query->where('compras_ordenes_facturas_pagos_proveedores_descripcion.id_proveedor_pago', '=', $datos['id_proveedor_pago']);
        }

        return $query->get();

    }
}
