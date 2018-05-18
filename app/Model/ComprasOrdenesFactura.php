<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ComprasOrdenesFactura extends Model
{
    protected $table = "compras_ordenes_facturas";

    protected $primaryKey = "id_orden_factura";
    public $timestamps = false;

    public function programacionPagos($datos)
    {
        $query = $this->from('compras_ordenes_facturas AS cof')
            ->select(
                'cof.*',
                'cp.nombre AS proveedor',
                'cp.plazo_credito'
            )
            ->leftJoin('cat_proveedores AS cp', 'cp.id_proveedor', '=', 'cof.id_proveedor')
            ->whereBetween('cof.fecha_' . $datos['tipo_fecha'], [$datos['fecha_inicial'], $datos['fecha_final']]);

        return $query->get();
    }

    public function porPagar($datos)
    {

        $query = $this->from("compras_ordenes_facturas as cof");
        $query->leftJoin("compras_ordenes as co", "co.id_orden", "=", "cof.id_orden");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "co.id_sucursal");
        $query->leftJoin("cat_proveedores as p", "p.id_proveedor", "=", "cof.id_proveedor");

        if (isset($datos["id_proveedor"])) {

            $select = [
                "co.id_orden",
                "cof.fecha_factura",
                "cof.fecha_vencimiento_factura",
                "cof.importe"
            ];

        } else {

            $select = [
                "co.id_sucursal",
                "p.id_proveedor",
                "p.nombre as proveedor",
                \DB::raw("ifnull(sum(cof.importe),0) as por_pagar")
            ];

        }
        $query->select($select);

        $query->whereIn("cof.estatus", ["pendiente"]);

        if (!empty($datos["id_sucursal"])) {

            $query->where("co.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["id_proveedor"])) {

            $query->where("cof.id_proveedor", $datos["id_proveedor"]);

        }

        if (!empty($datos["fecha_inicio"])) {

            $query->where("cof.fecha_captura_factura", ">=", $datos["fecha_inicio"]);

        }

        if (!empty($datos["fecha_final"])) {

            $query->where("cof.fecha_captura_factura", "<=", $datos["fecha_final"]);

        }

        if (empty($datos["id_proveedor"])) {

            $query->groupBy("cof.id_proveedor");
            $query->orderBy("por_pagar", "desc");

        } else {

            $query->orderBy("importe", "desc");

        }

        //dd($query->toSql());
        return $query->get();

    }

    public function pagoAnticipado($datos)
    {

        $query = $this->from("compras_ordenes_facturas as cof");
        $query->leftJoin("compras_ordenes as co", "co.id_orden", "=", "cof.id_orden");
        $query->leftJoin("cat_proveedores as cp", "cp.id_proveedor", "=", "co.id_proveedor");
        $query->leftJoin("cat_sucursales as cs", "cs.id_sucursal", "=", "co.id_sucursal");

        if (isset($datos["id_proveedor"])) {

            $select = [
                "co.id_orden",
                "cof.fecha_pago",
                "cof.importe",
                "co.estatus"
            ];

        } else {

            $select = [
                \DB::raw("(ifnull(sum(cof.importe), 0) - SUM(ifnull((SELECT sum((cod.precio * p_iva (aeod.id_producto)) * aeod.cantidad) FROM almacenes_entradas_ordenes_descripcion AS aeod LEFT JOIN compras_ordenes_descripcion AS cod ON cod.id_orden_descripcion = aeod.id_orden_descripcion WHERE aeod.id_orden = cof.id_orden),0))) AS pago_anticipado"),
                "co.id_sucursal",
                "cs.nombre as sucursal",
                "cp.nombre as proveedor",
                "cp.id_proveedor"
            ];

        }

        $query->select($select);

        $query->where("cof.estatus", "pagada");
        $query->where("cof.tipo_pago", "anticipado");

        if (!empty($datos["id_sucursal"])) {

            $query->where("co.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["fecha_inicio"])) {

            $query->where(\DB::raw("date(cof.fecha_pago)"), ">=", $datos["fecha_inicio"]);

        }

        if (!empty($datos["fecha_final"])) {

            $query->where(\DB::raw("date(cof.fecha_pago)"), "<=", $datos["fecha_final"]);

        }

        if (!empty($datos["id_proveedor"])) {

            $query->where("cof.id_proveedor", $datos["id_proveedor"]);
            $query->groupBy("co.id_orden");
            $query->orderBy("cof.importe", "desc");

        } else {

            $query->groupBy("cof.id_proveedor");
            $query->orderBy("pago_anticipado", "desc");

        }

        //dd($query->toSql());
        return $query->get();

    }

    public function pagadoPorLlegar($parametros)
    {
        $query = $this->from('compras_ordenes_facturas AS cof')
            ->select(
                'cof.fecha_pago',
                \DB::raw('SUM(cof.subtotal) AS monto'),
                \DB::raw('(SELECT SUM(aeod.cantidad * cod.precio) FROM almacenes_entradas_ordenes_descripcion AS aeod LEFT JOIN compras_ordenes_descripcion AS cod ON cod.id_orden_descripcion = aeod.id_orden_descripcion WHERE cod.id_orden = co.id_orden) AS ingresado'),
                'co.id_orden',
                'cs.nombre AS sucursal',
                'cp.nombre AS proveedor'
            )
            ->leftJoin('compras_ordenes AS co', 'co.id_orden', '=', 'cof.id_orden')
            ->leftJoin('cat_proveedores AS cp', 'cp.id_proveedor', '=', 'co.id_proveedor')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'co.id_sucursal')
            ->where('cof.estatus', 'pagada')
            ->where('cof.tipo_pago', 'anticipado')
            ->where('cof.emision_concepto', 'compra')
            ->having('monto', '>', 'ingresado');

        if (!empty($parametros['fecha_inicial'])) {
            $query = $query->where(\DB::raw('DATE(cof.fecha_pago)'), '>=', $parametros['fecha_inicial']);
        }

        if (!empty($parametros['fecha_final'])) {
            $query = $query->where(\DB::raw('DATE(cof.fecha_pago)'), '<=', $parametros['fecha_final']);
        }

        if (!empty($parametros['misma_fecha'])) {
            if ($parametros['misma_fecha']) {
                $query = $query->whereBetween(\DB::raw('DATE(co.fecha_orden)'), [$parametros['fecha_inicial'], $parametros['fecha_final']]);
            }
        }

        if (!empty($parametros['sucursal_id'])) {
            $query = $query->where('co.id_sucursal', $parametros['sucursal_id']);
        }

        return $query->groupBy('co.id_orden')
            ->get();
    }
}