<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PagosProveedores extends Model
{
    protected $table = "compras_ordenes_facturas_pagos_proveedores";

    protected $primaryKey = "id_proveedor_pago";
    public $timestamps = false;

    public function consultar($datos){
        $query = $this->leftJoin('cat_proveedores', 'compras_ordenes_facturas_pagos_proveedores.id_proveedor', '=', 'cat_proveedores.id_proveedor')
        ->leftJoin('cat_proveedores_cuentas','compras_ordenes_facturas_pagos_proveedores.id_proveedor_cuenta', '=', 'cat_proveedores_cuentas.id_proveedor_cuenta');

        $select = ['compras_ordenes_facturas_pagos_proveedores.*',
            \DB::raw('obtenerProveedorNombre(cat_proveedores.id_proveedor) as proveedorNombre'),
            \DB::raw('obtenerBancoNombre(cat_proveedores_cuentas.id_banco) as bancoNombre'),
            'cat_proveedores.email_proveedor'];

        $query = $query->select($select);
        if(!empty($datos["referencia_pago"])){
            $query->where('compras_ordenes_facturas_pagos_proveedores.referencia_pago','like','%'.$datos['referencia_pago'].'%');
        }

        if(!empty($datos["nombre"])){
            $query->where('cat_proveedores.nombre','like','%'.$datos['nombre'].'%');
        }

        if(!empty($datos["fechaInicio"])){
            $query->where(\DB::raw('date(compras_ordenes_facturas_pagos_proveedores.fecha_emision)'), '>=', $datos['fechaInicio']);
        }
        if(!empty($datos["fechaFinal"])){
            $query->where(\DB::raw('date(compras_ordenes_facturas_pagos_proveedores.fecha_emision)'), '<=', $datos['fechaFinal']);
        }

        return $query->get();

    }

    public function obtenerPagos($datos){
        $query = $this->leftJoin('cat_proveedores', 'compras_ordenes_facturas_pagos_proveedores.id_proveedor', '=', 'cat_proveedores.id_proveedor')
                ->leftJoin('cat_proveedores_cuentas','compras_ordenes_facturas_pagos_proveedores.id_proveedor_cuenta', '=', 'cat_proveedores_cuentas.id_proveedor_cuenta');
        $select = ['compras_ordenes_facturas_pagos_proveedores.*',
            \DB::raw('obtenerProveedorNombre(cat_proveedores.id_proveedor) as nombreProveedor'),
            \DB::raw('obtenerBancoNombre(cat_proveedores_cuentas.id_banco) as bancoNombre'),
            'cat_proveedores.email_proveedor',
        ];

        $query = $query->select($select);
//        if($datos["referencia_pago"]){
//            $query->where('compras_ordenes_facturas_pagos_proveedores.referencia_pago','like','%'.$datos['referencia_pago'].'%');
//        }

        if(!empty($datos["id_proveedor_pago"])){
            $query->where('compras_ordenes_facturas_pagos_proveedores.id_proveedor_pago', '=', $datos['id_proveedor_pago']);
        }
        if(!empty($datos["referencia_pago"])){
            $query->where('compras_ordenes_facturas_pagos_proveedores.referencia_pago', '=', $datos['referencia_pago']);
        }

        if(!empty($datos['compras'])){
            $query->where(\DB::raw('verificarPagoOrdenesCompra(compras_ordenes_facturas_pagos_proveedores.id_proveedor_pago)>0'));
        }

//        $query->gro

        if(!empty($datos['first']))
            return $query->first();
        else
            return $query->get();
    }


}
