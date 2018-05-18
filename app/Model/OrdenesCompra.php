<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrdenesCompra extends Model
{
    protected $table = 'compras_ordenes';
    protected $primaryKey = "id_orden";

    protected $fillable = [
        'id_cotizacion',
        'id_solicitud_cotizacion',
        'id_usuario',
        'id_proveedor',
        'id_sucursal',
        'fecha_orden',
        'estatus',
        'descuento',
        'subtotal_compra',
        'total_compra',
        'iva_compra',
        'iva_retenido',
        'isr_retenido',
        'estatus_facturas',
        'id_direccion',
        'observacion',
        'edicion',
        'precios_factura',
        'personalizada',
        'id_usuario_autorizo',
        'fecha_autorizacion',
        'id_usuario_aut_edicion',
        'fecha_aut_edicion',
        'id_usuario_edita',
        'fecha_edicion',
        'id_usuario_cancela',
        'fecha_cancelacion',
        'tipo_compra',
        'compra_especial',
        'programacion_pagos',
        'concepto_emision',
        'clasificacion_gasto_id',
        'clasificacion_compra',
        'venta_id',
        'validacion_gastos',
        "id_contrato",
        'uso_cfdi_id',
        'forma_pago_cfdi_id',
        'metodo_pago_cfdi_id',
        'condiciones_pago',
        "jat",
        "variacion_costo",
        'nueva_oc',
        "observaciones_internas",
        "observaciones_externas"
    ];

    public function buscar($datos)
    {

        //$usuario = \Session::get("usuario");

        $query = $this->leftJoin('cat_sucursales as s', 's.id_sucursal', '=', 'compras_ordenes.id_sucursal');
        $query->leftJoin('cat_proveedores as p', 'p.id_proveedor', '=', 'compras_ordenes.id_proveedor');
        $query->leftJoin('usuarios as u', 'u.id_usuario', '=', 'compras_ordenes.id_usuario');
        $query->leftJoin('usuarios as ua', 'ua.id_usuario', '=', 'compras_ordenes.id_usuario_autorizo');
        $query->leftJoin('compras_ordenes_provision as cpv', "cpv.compra_orden_id", "=", "compras_ordenes.id_orden");
        $query->leftJoin("gastosclasificacion as g", "g.id_gasto_clasificacion", "=", "compras_ordenes.clasificacion_gasto_id");

        $select = [
            'compras_ordenes.*',
            \DB::raw('ifnull(compras_ordenes.subtotal_compra, 0) as subtotal'),
            \DB::raw('ifnull(compras_ordenes.iva_compra, 0) as impuesto'),
            \DB::raw('ifnull(compras_ordenes.fecha_orden, "0000-00-00") as fecha'),
            \DB::raw('ifnull(compras_ordenes.fecha_autorizacion, "0000-00-00") as fecha_autorizacion'),
            \DB::raw('ifnull(ua.nombre, "N/A") as autorizo'),
            's.nombre as sucursal',
            'p.nombre as proveedor',
            'u.nombre as usuario',
            "precios_factura",
            "cpv.provision_id",
            \DB::raw("ifnull(compras_ordenes.observaciones_internas,'') as observaciones_internas"),
            \DB::raw("ifnull(compras_ordenes.observaciones_externas,'') as observaciones_externas"),
            \DB::raw("ifnull(cpv.compra_orden_id,0) as compra_orden_id"),
            \DB::raw("obtenerTotalOCE(compras_ordenes.id_orden) as total_oce"),
            \DB::raw("obtenerTotalOCIVA(compras_ordenes.id_orden) as total_oc"),
            \DB::raw("obtenerTotalOC(compras_ordenes.id_orden) as total"),
            \DB::raw("ifnull(compras_ordenes.total_compra,0) as total_compra"),
            \DB::raw("obtenerTotalOCPeso(compras_ordenes.id_orden) as total_pesos"),
            \DB::raw("ifnull(compras_ordenes.observacion,'') as observacion"),
            \DB::raw("ifnull(p.analisis_minimo,'') as analisis_minimo"),
            \DB::raw("ifnull(p.minimo_compra,'') as minimo_compra"),
            \DB::raw("ifnull(p.minimo_peso,'') as minimo_peso"),
            \DB::raw("(select count(*) from ordenes_compra_estatus as oce where orden_id = compras_ordenes.id_orden and oce.estado = 'pendiente') as validacionesPendientes"),
            \DB::raw("ifnull(g.clasificacion,'') as gasto_clasificacion")
        ];

        if (!empty($datos['id_orden'])) {
            $query->where('compras_ordenes.id_orden', $datos['id_orden']);
            if (!empty($datos["first"])) {
                $query->select($select);
                return $query->first();
            }
        }
        if (!empty($datos['id_usuario'])) {
            $query->where('compras_ordenes.id_usuario', $datos['id_usuario']);
        }
        if (!empty($datos['proveedor'])) {
            $query->where("p.nombre", 'like', '%' . $datos['proveedor'] . '%');
        }
        if (!empty($datos['id_sucursal'])) {
            $query->where('compras_ordenes.id_sucursal', $datos['id_sucursal']);
        }
        if (!empty($datos['estatus'])) {
            $query->where('compras_ordenes.estatus', $datos['estatus']);
        }
        if (!empty($datos['estatus_facturas'])) {
            $query->where('compras_ordenes.estatus_facturas', $datos['estatus_facturas']);
        }
        if (!empty($datos["concepto_emision"])) {
            $query->where("compras_ordenes.concepto_emision", "=", $datos["concepto_emision"]);
        }
        if (!empty($datos["fecha_inicio"])) {
            $query->where("compras_ordenes.fecha_orden", ">=", $datos["fecha_inicio"]);
        }
        if (!empty($datos["fecha_fin"])) {
            $query->where("compras_ordenes.fecha_orden", "<=", $datos["fecha_fin"]);
        }
        if (!empty($datos["validacion_pendiente"])) {
            if ($datos["validacion_pendiente"] == "si") {
                $query->having("validacionesPendientes", ">", 0);
            } else {
                $query->having("validacionesPendientes", "=", 0);
            }
        }
        if (!empty($datos["rol_id"])) {
            $select[] = \DB::raw("(SELECT IF((SELECT COUNT(*) FROM ordenes_compra_estatus AS oce3 WHERE oce3.jerarquia = (oce2.jerarquia-1) AND estado = 'pendiente' AND oce3.orden_id = oce2.orden_id) > 0, 'no', 'si') FROM ordenes_compra_estatus AS oce2 WHERE orden_id = compras_ordenes.id_orden AND rol_id = " . $datos['rol_id'] . " AND estado = 'pendiente' LIMIT 1) AS rolValidacion");

            $select[] = \DB::raw("(select if((select count(vcoc.id) from validaciones_costos_ordenes_compra vcoc where vcoc.orden_id = compras_ordenes.id_orden and vcoc.estado = 'pendiente' and vcoc.rol_id = ". $datos['rol_id'] .") > 0,'si','no')) as rolValidacionCosto");

            $query->whereNotIn('compras_ordenes.estatus', ['cancelada', 'finalizada'])
                ->having('rolValidacion', '=', 'si');

            $query->orHaving('rolValidacionCosto', '=', 'si');
        }

        $query->select($select);
        return $query->get();
    }

    public function buscarPDF($datos)
    {
        $query = $this->leftJoin('cat_sucursales as s', 's.id_sucursal', '=', 'compras_ordenes.id_sucursal');
        $query->leftJoin('cat_proveedores as p', 'p.id_proveedor', '=', 'compras_ordenes.id_proveedor');
        $query->leftJoin('usuarios as u', 'u.id_usuario', '=', 'compras_ordenes.id_usuario');
        $query->leftJoin('usuarios as ua', 'ua.id_usuario', '=', 'compras_ordenes.id_usuario_autorizo');
        $query->leftJoin('cat_estados as e', 'e.id_estado', '=', 's.id_estado');
        $query->leftJoin("compras_otras_direcciones_entregas as od", "od.id_direccion", "=", "compras_ordenes.id_direccion");
        $query->leftJoin("catalogos_usos_cfdi as uso", "uso.id", "=", "compras_ordenes.uso_cfdi_id");
        $query->leftJoin("catalogos_metodos_pagos_cfdi as metodo", "metodo.id", "=", "compras_ordenes.metodo_pago_cfdi_id");
        $query->leftJoin("catalogos_formas_pagos_cfdi as forma", "forma.id", "=", "compras_ordenes.forma_pago_cfdi_id");

        $query->select(
            'compras_ordenes.*',
            's.nombre as sucursal',
            \DB::raw('CONCAT(s.direccion, ", ", s.colonia, ", ", s.ciudad, ", ", e.estado) as direccion'),
            'p.nombre as proveedor',
            'u.nombre',
            'ua.nombre as autorizo',
            \DB::raw("obtenerTotalOC(compras_ordenes.id_orden) as subtotal_oc"),
            \DB::raw("obtenerTotalOCIVA(compras_ordenes.id_orden) as total_oc"),
            \DB::raw("ifnull(od.direccion,'') as otra_direccion"),
            \DB::raw("ifnull(obtenerSubtotalOCProveedor(compras_ordenes.id_orden),0) as subtotal_oc_proveedor"),
            \DB::raw("ifnull(obtenerTotalOCProveedor(compras_ordenes.id_orden),0) as total_oc_proveedor"),
            \DB::raw("ifnull(uso.clave,'') as uso_cfdi_clave"),
            \DB::raw("ifnull(uso.descripcion,'') as uso_cfdi_descripcion"),
            \DB::raw("ifnull(metodo.clave,'') as metodo_cfdi_clave"),
            \DB::raw("ifnull(metodo.descripcion,'') as metodo_cfdi_descripcion"),
            \DB::raw("ifnull(forma.clave,'') as forma_cfdi_clave"),
            \DB::raw("ifnull(forma.descripcion,'') as forma_cfdi_descripcion"),
            \DB::raw("ifnull(compras_ordenes.condiciones_pago,'') as condiciones_pago")
        );

        if (!empty($datos['id_orden'])) {
            $query->where('compras_ordenes.id_orden', $datos['id_orden']);
        }

        //dd($query->toSql());
        return $query->first();
    }

    public function buscarOcAutorizadas($parametros)
    {
        $query = $this->from('compras_ordenes AS co')
            ->select(
                \DB::raw('DATEDIFF(DATE(aeo.fecha_entrada),DATE(co.fecha_autorizacion)) AS diferencia_dias')
            )
            ->leftJoin('compras_ordenes_descripcion AS cod', 'cod.id_orden', '=', 'co.id_orden')
            ->leftJoin('almacenes_entradas_ordenes_descripcion AS aeod', 'aeod.id_orden', '=', 'co.id_orden')
            ->leftJoin('almacenes_entradas_ordenes AS aeo', 'aeo.id_entrada_orden', '=', 'aeod.id_entrada_orden')
            ->where('cod.id_sucursal', $parametros['sucursal_id'])
            ->where('cod.id_producto', $parametros['producto_id'])
            ->where('aeod.id_producto', $parametros['producto_id'])
            ->orderBy('aeod.id_entrada_orden', 'DESC')
            ->take($parametros['cantidad']);

        return $query->get()->toArray();
    }

    public function ocPagadas($parametros)
    {
        $query = $this->from('compras_ordenes AS co')
            ->select(
                'co.*',
                'cof.fecha_pago',
                \DB::raw('SUM(cof.subtotal) AS subtotal')
            )
            ->leftJoin('compras_ordenes_facturas AS cof', 'cof.id_orden', '=', 'co.id_orden');

        if (!empty($parametros['sucursal_id'])) {
            $query = $query->where('id_sucursal', $parametros['sucursal_id']);
        }

        $query = $query->whereBetween(\DB::raw('DATE(cof.fecha_pago)'), [$parametros['fecha_inicial'], $parametros['fecha_final']])
            //->whereNotBetween(\DB::raw('DATE(co.created_at)'), [$parametros['fecha_inicial'], $parametros['fecha_final']])
            ->where('co.concepto_emision', 'compra')
            ->where('cof.estatus', 'pagada')
            ->groupBy('co.id_orden');

        if (!empty($parametros['anticipos'])) {
            if ($parametros['anticipos'] == "si") {
                $query = $query->where('cof.tipo_pago', 'anticipado');
            } else {
                $query = $query->where('cof.tipo_pago', '!=', 'anticipado');
            }
        }

        return $query->get();
    }

    public function ocPendientes($parametros)
    {
        $query = $this->from('compras_ordenes AS co')
            ->select(
                'co.id_orden',
                'co.estatus',
                'co.fecha_orden',
                'co.subtotal_compra',
                'cp.nombre AS proveedor',
                'cs.nombre AS sucursal'
            )
            ->leftJoin('cat_proveedores AS cp', 'cp.id_proveedor', '=', 'co.id_proveedor')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'co.id_sucursal')
            ->where('co.estatus', 'autorizada')
            ->where('concepto_emision', 'compra');

        if (!empty($parametros['fecha_inicial'])) {
            $query = $query->where(\DB::raw('DATE(co.fecha_orden)'), '>=', $parametros['fecha_inicial']);
        }

        if (!empty($parametros['fecha_final'])) {
            $query = $query->where(\DB::raw('DATE(co.fecha_orden)'), '<=', $parametros['fecha_final']);
        }

        if (!empty($parametros['sucursal_id'])) {
            $query = $query->where('co.id_sucursal', $parametros['sucursal_id']);
        }

        return $query->groupBy('co.id_orden')
            ->get();
    }

    public function ocGastos(array $parametros)
    {
        $query = $this->from('compras_ordenes AS co')
            ->select(
                'co.id_orden',
                'co.fecha_orden',
                'co.subtotal_compra',
                'cp.nombre AS proveedor',
                'cs.nombre AS sucursal'
            )
            ->leftJoin('cat_proveedores AS cp', 'cp.id_proveedor', '=', 'co.id_proveedor')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'co.id_sucursal')
            ->where('co.estatus', 'finalizada')
            ->where('concepto_emision', 'gasto');

        if (!empty($parametros['fecha_inicial'])) {
            $query = $query->where(\DB::raw('DATE(co.fecha_orden)'), '>=', $parametros['fecha_inicial']);
        }

        if (!empty($parametros['fecha_final'])) {
            $query = $query->where(\DB::raw('DATE(co.fecha_orden)'), '<=', $parametros['fecha_final']);
        }

        if (!empty($parametros['sucursal_id'])) {
            $query = $query->where('co.id_sucursal', $parametros['sucursal_id']);
        }

        return $query->groupBy('co.id_orden')
            ->get();
    }
}