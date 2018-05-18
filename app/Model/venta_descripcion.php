<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class venta_descripcion extends Model
{
    //
    protected $table = "ventas_descripcion";
    protected $primaryKey = "id_descripcion";
    public $timestamps = false;

    public function analisisCompra($datos)
    {

        $query = $this->leftJoin("ventas as v", "v.id_venta", "=", "ventas_descripcion.id_venta");
        $query->leftJoin("productos as p", "p.id_producto", "=", "ventas_descripcion.id_producto");

        $select = [
            \DB::raw("sum(ventas_descripcion.cantidad*ventas_descripcion.precio) - (devolucionesSucursalProducto(0,ventas_descripcion.id_producto,'" . $datos["fecha_inicio"] . "','" . $datos["fecha_termino"] . "',1)) as ventas"),
            'p.codigo_producto',
            'p.descripcion',
            'p.factor_conversion',
            "p.id_producto"

        ];

        $query->select($select);

        $query->where(\DB::raw("date(v.fecha)"), ">=", $datos["fecha_inicio"]);
        $query->where(\DB::raw("date(v.fecha)"), "<=", $datos["fecha_termino"]);

        if (!empty($datos["id_sucursal"])) {
            $query->whereIn("v.id_sucursal", $datos["id_sucursal"]);
        }

        if (!empty($datos["id_linea"])) {
            $query->whereIn("p.id_linea", $datos["id_linea"]);
        }
        if (!empty($datos["id_familia"])) {
            $query->whereIn("p.id_familia", $datos["id_familia"]);
        }
        if (!empty($datos["codigo_producto_array"])) {
            $query->whereIn("p.codigo_producto", $datos["codigo_producto_array"]);
        }
        $query->groupBy("p.id_producto");
        $query->orderBy("ventas", "desc");


        return $query->get();

    }

    public function analisisCompraAlmacenes($datos)
    {

        $query = $this->leftJoin("ventas as v", "v.id_venta", "=", "ventas_descripcion.id_venta");
        $query->leftJoin("productos as p", "p.id_producto", "=", "ventas_descripcion.id_producto");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "ventas_descripcion.id_almacen");
        $query->leftJoin("cat_sucursales as cs", "cs.id_sucursal", "=", "a.id_sucursal");
        $query->leftJoin("almacenes_existencias as ax", function ($join) {
            $join->on("ax.id_almacen", "=", "a.id_almacen")
                ->on("ax.id_producto", "=", "p.id_producto");
        });

        $select = [
            \DB::raw("sum(ventas_descripcion.cantidad) - (devolucionesAlmacenProducto(ventas_descripcion.id_almacen,ventas_descripcion.id_producto,'" . $datos["fecha_inicio"] . "','" . $datos["fecha_termino"] . "')) as ventas"),
            'p.codigo_producto',
            'p.descripcion',
            'p.unidad_compra',
            'p.factor_conversion',
            'p.codigo_producto',
            'p.descripcion',
            'p.id_producto',
            "a.almacen",
            "a.id_almacen",
            "a.id_sucursal",
            "cs.nombre as sucursal",
            "ax.existencia",
            "ax.id_existencia",
            "ax.stock_maximo",
            "ax.stock_minimo",
            "a.bandera",
            \DB::raw("productoCompraSolicitudAlmacen(ventas_descripcion.id_almacen,ventas_descripcion.id_producto,'ps') as solicitud"),
            \DB::raw("compraOptenerTransferenciasAlmacenProducto(ventas_descripcion.id_producto,ventas_descripcion.id_almacen) as tranferido"),
            \DB::raw("obtenerCompraProductoAlmacen(ventas_descripcion.id_producto,ventas_descripcion.id_almacen) as compras"),
            \DB::raw("obtener_conversion(ventas_descripcion.id_producto) as producto_padre_id")


        ];

        $query->select($select);
        $query->whereNotNull("ax.id_existencia");

        $query->where(\DB::raw("date(v.fecha)"), ">=", $datos["fecha_inicio"]);
        $query->where(\DB::raw("date(v.fecha)"), "<=", $datos["fecha_termino"]);

        if (!empty($datos["id_sucursal"])) {
            $query->whereIn("v.id_sucursal", $datos["id_sucursal"]);
        }

        if (!empty($datos["id_linea"])) {
            $query->whereIn("p.id_linea", $datos["id_linea"]);
        }
        if (!empty($datos["id_familia"])) {
            $query->whereIn("p.id_familia", $datos["id_familia"]);
        }

        if (!empty($datos["id_producto"])) {
            $query->where("p.id_producto", "=", $datos["id_producto"]);
        }
        if (!empty($datos["codigo_producto_array"])) {
            $query->whereIn("p.codigo_producto", $datos["codigo_producto_array"]);
        }

        $query->groupBy("ventas_descripcion.id_almacen");

        $query->orderBy("ventas", "desc");
        if (!empty($datos["id_existencia"]))
            $query->where("ax.id_existencia", $datos["id_existencia"]);

        if (!empty($datos["first"]))
            return $query->first();


        return $query->get();

    }

    public function buscarDatos($datos)
    {

        $query = $this->leftJoin("productos as p", "p.id_producto", "=", "ventas_descripcion.id_producto");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "ventas_descripcion.id_sucursal");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "ventas_descripcion.id_almacen");
        $query->leftJoin("iva as i", "i.id_iva", "=", "p.id_iva");

        $query->select(
            "ventas_descripcion.id_descripcion",
            "ventas_descripcion.id_venta",
            "ventas_descripcion.id_sucursal",
            "s.nombre as sucursal",
            "ventas_descripcion.id_almacen",
            "a.almacen",
            "ventas_descripcion.id_producto",
            "p.codigo_producto",
            "p.descripcion_corta",
            "p.descripcion",
            "i.porcentaje",
            "ventas_descripcion.cantidad",
            "ventas_descripcion.precio",
            "ventas_descripcion.iva",
            "p.unidad_compra as unidad_medida",
            "a.bandera as tipo_almacen"
        );

        $query->where("id_venta", "like", $datos["id_venta"]);

        if (!empty($datos["id_sucursal"])) {
            $query->where("ventas_descripcion.id_sucursal", $datos["id_sucursal"]);
        }
        if (!empty($datos["id_almacen"])) {
            $query->where("ventas_descripcion.id_almacen", $datos["id_almacen"]);
        }
        if (!empty($datos["tipo"])) {
            $query->where("a.bandera", $datos["tipo"]);
        }

        //dd($query->toSql());

        return $query->get();

    }

    public function minimoCompra($datos)
    {

        $query = $this->leftJoin("ventas as v", "v.id_venta", "=", "ventas_descripcion.id_venta");
        $query->leftJoin("productos as p", "p.id_producto", "=", "ventas_descripcion.id_producto");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "ventas_descripcion.id_almacen");
        $query->leftJoin("cat_sucursales as cs", "cs.id_sucursal", "=", "a.id_sucursal");
        $query->leftJoin("almacenes_existencias as ax", function ($join) {
            $join->on("ax.id_almacen", "=", "a.id_almacen")
                ->on("ax.id_producto", "=", "p.id_producto");
        });


        $select = [
            \DB::raw("sum(ventas_descripcion.cantidad) - (devolucionesAlmacenProducto(ventas_descripcion.id_almacen,ventas_descripcion.id_producto,'" . $datos["fecha_inicio"] . "','" . $datos["fecha_termino"] . "')) as ventas"),
            'p.codigo_producto',
            'p.unidad_compra',
            'p.descripcion',
            'p.factor_conversion',
            'p.codigo_producto',
            'p.descripcion',
            'p.id_producto',
            "a.almacen",
            "a.id_almacen",
            "a.id_sucursal",
            "cs.nombre as sucursal",
            "ax.existencia",
            "ax.id_existencia",
            "ax.stock_maximo",
            "ax.stock_minimo",
            "a.bandera",


        ];

        $query->select($select);

        $query->where(\DB::raw("date(v.fecha)"), ">=", $datos["fecha_inicio"]);
        $query->where(\DB::raw("date(v.fecha)"), "<=", $datos["fecha_termino"]);

        if (!empty($datos["id_sucursal"])) {
            $query->where("v.id_sucursal", $datos["id_sucursal"]);
        }

        if (!empty($datos["id_linea"])) {
            $query->where("p.id_linea", $datos["id_linea"]);
        }
        if (!empty($datos["id_familia"])) {
            $query->where("p.id_familia", $datos["id_familia"]);
        }
        if (!empty($datos["codigo_producto"])) {
            $query->where("p.codigo_producto", "=", $datos["codigo_producto"]);
        }
        if (!empty($datos["codigo_producto_array"])) {
            $query->whereIn("p.codigo_producto", $datos["codigo_producto_array"]);
        }


        $query->groupBy("ax.id_existencia");

        $query->orderBy("ax.id_sucursal", "desc");
//        dd($query->toSql());

        return $query->get();

    }

    public function tendencias($datos)
    {
        $conf = informacionFormatos::first();
        $fecha = $datos['fecha'];
        $fechaInicialMes = Carbon::parse($datos['fecha'])->startOfMonth()->toDateString();
        $fechaFinalMesAnterior = Carbon::parse($datos['fecha'])->subMonth()->endOfMonth()->toDateString();
        $fechaInicialTendencia = Carbon::parse($datos['fecha'])->subMonths($conf->meses_tendencia)->startOfMonth()->toDateString();

        $query = $this->from('ventas_descripcion AS vd')
            ->select(
                \DB::raw('SUM(vd.cantidad) AS ventas_unidad'),
                \DB::raw('(SELECT SUM(vd2.cantidad * vd2.precio) AS cantidad FROM ventas_descripcion AS vd2 LEFT JOIN ventas AS v2 ON v2.id_venta = vd2.id_venta WHERE v2.id_sucursal = v.id_sucursal AND (date(v2.fecha) >= "' . $fechaInicialMes . '" AND date(v2.fecha) <= "' . $fecha . '")) AS ventas'),
                \DB::raw('(SELECT SUM(vd2.cantidad * vd2.costo) AS cantidad FROM ventas_descripcion AS vd2 LEFT JOIN ventas AS v2 ON v2.id_venta = vd2.id_venta WHERE v2.id_sucursal = v.id_sucursal AND (date(v2.fecha) >= "' . $fechaInicialMes . '" AND date(v2.fecha) <= "' . $fecha . '")) AS costo'),
                \DB::raw('(SELECT SUM(d.subtotal) AS devoluciones FROM devoluciones AS d LEFT JOIN ventas AS v2 ON v2.id_venta = d.id_venta WHERE v2.id_sucursal = v.id_sucursal AND (date(d.fecha) >= "' . $fechaInicialMes . '" AND date(d.fecha) <= "' . $fecha . '")) AS devoluciones'),
                \DB::raw('(SELECT SUM(dd.cantidad * vd.costo) AS devoluciones_descripcion FROM devoluciones_descripcion AS dd LEFT JOIN devoluciones AS d2 ON dd.id_devolucion = d2.id_devolucion LEFT JOIN ventas AS v2 ON v2.id_venta = d2.id_venta LEFT JOIN ventas_descripcion AS vd ON vd.id_venta = v2.id_venta AND vd.id_producto = dd.id_producto WHERE v2.id_sucursal = v.id_sucursal AND (date(d2.fecha) >= "' . $fechaInicialMes . '" AND date(d2.fecha) <= "' . $fecha . '")) AS devoluciones_costo'),
                \DB::raw('(SELECT SUM(dd.cantidad) AS devoluciones_descripcion FROM devoluciones_descripcion AS dd LEFT JOIN devoluciones AS d2 ON dd.id_devolucion = d2.id_devolucion LEFT JOIN ventas AS v2 ON v2.id_venta = d2.id_venta LEFT JOIN ventas_descripcion AS vd ON vd.id_venta = v2.id_venta AND vd.id_producto = dd.id_producto WHERE (v2.id_sucursal = v.id_sucursal) AND (date(d2.fecha) >= "' . $fechaInicialTendencia . '" AND date(d2.fecha) <= "' . $fechaFinalMesAnterior . '")) AS devoluciones_unidades'),
                \DB::raw('(SELECT SUM(vd2.cantidad) AS cantidad FROM ventas_descripcion AS vd2 LEFT JOIN ventas AS v2 ON v2.id_venta = vd2.id_venta WHERE v2.id_sucursal = v.id_sucursal AND (date(v2.fecha) >= "' . $fechaInicialMes . '" AND date(v2.fecha) <= "' . $fecha . '")) AS ventas_actuales'),
                \DB::raw('(SELECT SUM(dd.cantidad) AS devoluciones_descripcion FROM devoluciones_descripcion AS dd LEFT JOIN devoluciones AS d2 ON dd.id_devolucion = d2.id_devolucion LEFT JOIN ventas AS v2 ON v2.id_venta = d2.id_venta LEFT JOIN ventas_descripcion AS vd ON vd.id_venta = v2.id_venta AND vd.id_producto = dd.id_producto WHERE (v2.id_sucursal = v.id_sucursal) AND (date(d2.fecha) >= "' . $fechaInicialMes . '" AND date(d2.fecha) <= "' . $fecha . '")) AS devoluciones_actuales'),
                'cs.nombre AS sucursal',
                \DB::raw($conf->meses_tendencia . " AS meses_tendencia")
            )
            ->leftJoin('ventas AS v', 'v.id_venta', '=', 'vd.id_venta')
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'vd.id_producto')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'v.id_sucursal')
            ->whereBetween(\DB::raw('DATE(v.fecha)'), [$fechaInicialTendencia, $fechaFinalMesAnterior]);

        if (!empty($datos['familia_id'])) {
            $query = $query->where('p.id_familia', $datos['familia_id']);
        }

        if (!empty($datos['linea_id'])) {
            $query = $query->where('p.id_linea', $datos['linea_id']);
        }

        if (!empty($datos['categoria_id'])) {
            $query = $query->where('p.id_categoria', $datos['categoria_id']);
        }

        if (!empty($datos['producto_id'])) {
            $query = $query->where('p.id_producto', $datos['producto_id']);
        }

        $query = $query->groupBy('cs.id_sucursal');

        return $query->get();
    }

    public function buscarTotales($datos)
    {

        $query = $this->from("ventas_descripcion as vd");
        //$query->leftJoin("ventas as v", "v.id_venta", "=", "vd.id_venta");
        $query->leftJoin("productos as p", "p.id_producto", "=", "vd.id_producto");

        $query->select(
            \DB::raw("ifnull(sum(vd.cantidad),0) as total_ventas")
        );

        if (!empty($datos["id_sucursal"])) {

            $query->where("vd.id_sucursal", $datos["id_sucursal"]);

        }
        if (!empty($datos["id_familia"])) {

            if (count($datos["id_familia"]) > 1) {

                $query->whereIn("p.id_familia", $datos["id_familia"]);

            } else {

                $query->where("p.id_familia", $datos["id_familia"]);

            }

        }
        if (!empty($datos["id_linea"])) {

            if (count($datos["id_linea"]) > 1) {

                $query->whereIn("p.id_linea", $datos["id_linea"]);

            } else {

                $query->where("p.id_linea", $datos["id_linea"]);

            }

        }
        if (!empty($datos["fecha_inicio"])) {

            $query->where("vd.fecha", ">=", $datos["fecha_inicio"]);

        }
        if (!empty($datos["fecha_fin"])) {

            $query->where("vd.fecha", "<=", $datos["fecha_fin"]);

        }

        $query->whereRaw("!ventaTieneDevolucion(vd.id_venta)");

        //dd($query->toSql());
        return $query->first();

    }

    public function buscarTotalesVentas($datos)
    {

        $query = $this->from("ventas_descripcion as vd")
            ->leftJoin("productos as p", "p.id_producto", "=", "vd.id_producto")
            ->select(
                \DB::raw("ifnull(sum(vd.cantidad*vd.precio),0) as total_ventas")
            );

        if (!empty($datos["id_sucursal"])) {
            $query->where("vd.id_sucursal", $datos["id_sucursal"]);
        }

        if (!empty($datos["id_familia"])) {
            if (count($datos["id_familia"]) > 1) {
                $query->whereIn("p.id_familia", $datos["id_familia"]);
            } else {
                $query->where("p.id_familia", $datos["id_familia"]);
            }
        }

        if (!empty($datos["id_linea"])) {
            if (count($datos["id_linea"]) > 1) {
                $query->whereIn("p.id_linea", $datos["id_linea"]);
            } else {
                $query->where("p.id_linea", $datos["id_linea"]);
            }
        }

        if (!empty($datos["fecha_inicio"])) {
            $query->where(\DB::raw("DATE(vd.fecha)"), ">=", $datos["fecha_inicio"]);
        }

        if (!empty($datos["fecha_fin"])) {
            $query->where(\DB::raw("DATE(vd.fecha)"), "<=", $datos["fecha_fin"]);
        }

        $query = $query->whereRaw("!ventaTieneDevolucion(vd.id_venta)")
            ->whereRaw('!(SELECT COUNT(*) FROM tae_detalles WHERE tae_detalles.id_venta = vd.id_venta)');

        return $query->first();
    }

    public function ventasVendedor($datos)
    {

        $query = $this->leftJoin("ventas as v", "v.id_venta", "=", "ventas_descripcion.id_venta");
        $query->leftJoin("ventas_pagos as vp", "vp.id_venta", "=", "v.id_venta");
        $query->leftJoin("productos as p", "p.id_producto", "=", "ventas_descripcion.id_producto");
        $query->leftJoin("clientes as c", "c.id_cliente", "=", "v.id_cliente");
        $query->leftJoin("usuarios as u", "u.id_usuario", "=", "v.id_usuario");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "u.id_empleado");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "ventas_descripcion.id_sucursal");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "ventas_descripcion.id_almacen");

        $query->select(
            "s.nombre AS sucursal",
            "vp.tipo",
            \DB::raw("CONCAT(e.nombre,' ',e.apaterno,' ',e.amaterno) AS vendedor"),
            "c.nombre AS cliente",
            "c.nombre AS receptor",
            "ventas_descripcion.id_venta AS folio_venta",
            "v.fecha",
            "p.codigo_producto",
            "p.descripcion",
            "ventas_descripcion.precio",
            "ventas_descripcion.cantidad",
            \DB::raw("(ventas_descripcion.precio * ventas_descripcion.cantidad) AS total")
        );

        if (!empty($datos["id_sucursal"])) {

            $query->where("ventas_descripcion.id_sucursal", $datos["id_sucursal"]);

        }
        if (!empty($datos["id_usuario"])) {

            $query->where("v.id_usuario", $datos["id_usuario"]);

        }
        if (!empty($datos["fecha_inicio"])) {

            $query->where("v.fecha", ">=", $datos["fecha_inicio"] . " 00:00:00");

        }
        if (!empty($datos["fecha_fin"])) {

            $query->where("v.fecha", "<=", $datos["fecha_fin"] . " 23:59:59");

        }

        $query->orderBy("s.nombre", "asc");

        //dd($query->toSql());
        return $query->get();

    }

    public function cantidadesVentasAtipicas($datos)
    {

        $query = $this->select(
            \DB::raw('COUNT(*) AS total'),
            \DB::raw('(SELECT COUNT(*) FROM ventas_descripcion WHERE DATE(fecha) BETWEEN "' . $datos['fecha1'] . '" AND "' . $datos['fecha2'] . '" AND id_sucursal = ' . $datos['sucursal_id'] . ' AND id_producto = ' . $datos['producto_id'] . ' AND !(SELECT COUNT(*) FROM devoluciones WHERE ventas_descripcion.id_venta = devoluciones.id_venta AND estatus_devolucion = "pagada") AND cantidad <= 6) AS menor_igual_6'),
            \DB::raw('(SELECT COUNT(*) FROM ventas_descripcion WHERE DATE(fecha) BETWEEN "' . $datos['fecha1'] . '" AND "' . $datos['fecha2'] . '" AND id_sucursal = ' . $datos['sucursal_id'] . ' AND id_producto = ' . $datos['producto_id'] . ' AND !(SELECT COUNT(*) FROM devoluciones WHERE ventas_descripcion.id_venta = devoluciones.id_venta AND estatus_devolucion = "pagada") AND cantidad >= 7 AND cantidad <= 10) AS mayor_igual_7_menor_igual_10'),
            \DB::raw('(SELECT COUNT(*) FROM ventas_descripcion WHERE DATE(fecha) BETWEEN "' . $datos['fecha1'] . '" AND "' . $datos['fecha2'] . '" AND id_sucursal = ' . $datos['sucursal_id'] . ' AND id_producto = ' . $datos['producto_id'] . ' AND !(SELECT COUNT(*) FROM devoluciones WHERE ventas_descripcion.id_venta = devoluciones.id_venta AND estatus_devolucion = "pagada") AND cantidad > 10) AS mayor_10')
        )
            ->whereBetween(\DB::raw('DATE(fecha)'), [$datos['fecha1'], $datos['fecha2']])
            ->where('id_sucursal', $datos['sucursal_id'])
            ->where('id_producto', $datos['producto_id'])
            ->whereRaw('!(SELECT COUNT(*) FROM devoluciones WHERE ventas_descripcion.id_venta = devoluciones.id_venta AND estatus_devolucion = "pagada")')
            ->whereRaw('!(SELECT COUNT(*) FROM tae_detalles WHERE tae_detalles.id_venta = ventas_descripcion.id_venta)');

        return $query->first();
    }

    public function buscar($datos)
    {
        $query = $this->select(
            'cantidad',
            'id_producto'
        )
            ->where('id_sucursal', $datos['sucursal_id'])
            ->whereBetween(\DB::raw('DATE(fecha)'), [$datos['fecha1'], $datos['fecha2']])
            ->whereRaw('!ventaTieneDevolucion(ventas_descripcion.id_venta)')
            ->whereRaw('!(SELECT COUNT(*) FROM tae_detalles WHERE tae_detalles.id_venta = ventas_descripcion.id_venta)')
            ->orderBy('ventas_descripcion.id_producto');

        return $query->get();
    }

    public function atipicas($datos)
    {
        $query = $this->select(
            'cantidad'
        )
            ->whereBetween(\DB::raw('DATE(fecha)'), [$datos['fecha1'], $datos['fecha2']])
            ->where('id_producto', $datos['producto_id'])
            ->where('id_sucursal', $datos['sucursal_id'])
            ->whereRaw('!ventaTieneDevolucion(ventas_descripcion.id_venta)')
            ->whereRaw('!(SELECT COUNT(*) FROM tae_detalles WHERE tae_detalles.id_venta = ventas_descripcion.id_venta)')
            ->lists('cantidad');

        return $query->toArray();
    }

    public function ventasTotales($datos)
    {

        $query = $this->from('ventas_descripcion AS vd')
            ->select(
                \DB::raw('SUM(vd.cantidad * vd.precio) AS total_ventas'),
                'vd.id_sucursal'
            )
            ->leftJoin('almacenes_existencias AS ae', function ($join) {
                $join->on('ae.id_producto', '=', 'vd.id_producto')
                    ->on('ae.id_sucursal', '=', 'vd.id_sucursal')
                    ->on('ae.id_almacen', '=', 'vd.id_almacen');
            })
            ->whereRaw("!ventaTieneDevolucion(vd.id_venta)")
            ->whereBetween(\DB::raw('DATE(vd.fecha)'), [$datos['fecha_inicio'], $datos['fecha_fin']])
            ->whereRaw('!(SELECT COUNT(*) FROM tae_detalles WHERE tae_detalles.id_venta = vd.id_venta)')
            ->where('ae.rotacion_mensual', 'Si');

        if (isset($datos['sucursal_id'])) {
            return $query->where('vd.id_sucursal', $datos['sucursal_id'])
                ->first();
        } else {
            $query = $query->groupBy('vd.id_sucursal');
        }

        //dd($query->toSql());

        return $query->get();
    }

    public function buscarVentasProductos($datos)
    {
        $query = $this->from('ventas_descripcion AS vd')
            ->select(
                \DB::raw('SUM(vd.cantidad * vd.precio) AS totalVenta'),
                'vd.id_producto',
                'vd.id_almacen',
                'ae.id_existencia',
                \DB::raw('COUNT(*) AS cantidad_venta'),
                \DB::raw('p_costo(vd.id_producto) AS costo'),
                'tc.id_producto_origen',
                'tc.conversion'
            )
            ->leftJoin('almacenes_existencias AS ae', function ($join) {
                $join->on('ae.id_producto', '=', 'vd.id_producto')
                    ->on('ae.id_almacen', '=', 'vd.id_almacen');
            })
            ->leftJoin('transferencias_conversiones AS tc', 'tc.id_producto_destino', '=', 'vd.id_producto')
            ->whereBetween(\DB::raw('DATE(fecha)'), [$datos['fecha_inicio'], $datos['fecha_fin']])
            ->whereRaw('!ventaTieneDevolucion(vd.id_venta)')
            ->whereRaw('!(SELECT COUNT(*) FROM tae_detalles WHERE tae_detalles.id_venta = vd.id_venta)')
            ->where('ae.rotacion_mensual', 'Si');

        if (isset($datos['sucursal_id'])) {
            $query = $query->where('vd.id_sucursal', $datos['sucursal_id'])
                ->groupBy('vd.id_producto', 'vd.id_almacen')
                ->orderBy('vd.id_producto', 'DESC')
                ->orderBy('totalVenta', 'DESC');
        } else {
            $query = $query->groupBy('vd.id_producto')
                ->orderBy('totalVenta', 'DESC');
        }

        if (isset($datos['producto_id'])) {
            $query = $query->where('vd.id_producto', $datos['producto_id']);
            return $query->first();
        }

        return $query->get();
    }

    public function consultaVentas8020()
    {

        $query = $this->from("ventas_descripcion as vd");
        $query->leftJoin("ventas as v", "v.id_venta", "=", "vd.id_venta");
        $query->leftJoin("productos as p", "p.id_producto", "=", "vd.id_producto");
        $query->leftJoin("informacion_formatos as f", function ($join) {
            $join->where("f.id_informacion", "=", 1);
        });

        $query->select(
            \DB::raw("(SUM(vd.cantidad*vd.precio) -  IFNULL((select SUM(dd.cantidad*sin_iva) from devoluciones_descripcion as dd LEFT JOIN devoluciones as d on (dd.id_devolucion = d.id_devolucion) where dd.id_producto = vd.id_producto and dd.id_almacen = vd.id_almacen and d.estatus_devolucion in ('Pagada') and DATE(d.fecha) >=DATE_SUB(CURDATE(),INTERVAL f.dias_anteriores_analisis day) and DATE(d.fecha) <= CURDATE()),0)) as venta")
        );

        $query->where("p.id_familia", "<>", 18);

        #$query->where(\DB::raw("date(v.fecha)"), "<=", \DB::raw("curdate()"));
        $query->where(\DB::raw("date(v.fecha)"), "<=", \DB::raw("CURDATE()"));

        #$query->where(\DB::raw("date(v.fecha)"), ">=", \DB::raw("date_sub(curdate(),interval f.dias_anteriores_analisis day)"));
        $query->where(\DB::raw("date(v.fecha)"), ">=", \DB::raw("date_sub(CURDATE(),interval f.dias_anteriores_analisis day)"));

        $query->whereNotNull("vd.id_almacen");
        #$query->where("vd.id_almacen", 14);

        return $query->first();

    }

    public function consultaVentas8020Productos()
    {

        $query = $this->from("ventas_descripcion as vd");
        $query->leftJoin("ventas as v", "v.id_venta", "=", "vd.id_venta");
        $query->leftJoin("productos as p", "p.id_producto", "=", "vd.id_producto");
        $query->leftJoin("informacion_formatos as f", function ($join) {
            $join->where("f.id_informacion", "=", 1);
        });

        $query->select(
            "p.codigo_producto",
            "p.descripcion",
            "p.factor_conversion",
            "p.id_producto",
            "p.id_linea",
            "p.id_familia",
            "v.id_sucursal",
            "vd.id_almacen",
            \DB::raw("(SUM(vd.cantidad*vd.precio) -  IFNULL((select SUM(dd.cantidad*sin_iva) from devoluciones_descripcion as dd LEFT JOIN devoluciones as d on (dd.id_devolucion = d.id_devolucion) where dd.id_producto = vd.id_producto and dd.id_almacen = vd.id_almacen and d.estatus_devolucion in ('Pagada') and DATE(d.fecha) >=DATE_SUB(CURDATE(),INTERVAL f.dias_anteriores_analisis day) and DATE(d.fecha) <= CURDATE()),0)) as venta")
        );

        $query->where("p.id_familia", "<>", 18);

        #$query->where(\DB::raw("date(v.fecha)"), "<=", \DB::raw("curdate()"));
        $query->where(\DB::raw("date(v.fecha)"), "<=", \DB::raw("CURDATE()"));

        #$query->where(\DB::raw("date(v.fecha)"), ">=", \DB::raw("date_sub(curdate(),interval f.dias_anteriores_analisis day)"));
        $query->where(\DB::raw("date(v.fecha)"), ">=", \DB::raw("date_sub(CURDATE(),interval f.dias_anteriores_analisis day)"));

        $query->where("v.atipica", "=", "no");

        $query->whereNotNull("vd.id_almacen");
        #$query->where("vd.id_almacen", 14);

        $query->groupBy("p.id_producto");

//        $q = $query->toSql();
//        \Log::debug($q);
//        throw new \Exception("AQUI");

        return $query->get();
    }

    public function consultaVentas8020Producto($id_producto)
    {

        $query = $this->from("ventas_descripcion as vd");
        $query->leftJoin("ventas as v", "v.id_venta", "=", "vd.id_venta");
        $query->leftJoin("productos as p", "p.id_producto", "=", "vd.id_producto");
        $query->leftJoin("informacion_formatos as f", function ($join) {
            $join->where("f.id_informacion", "=", 1);
        });
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "vd.id_almacen");
        $query->leftJoin("almacenes_existencias as ae", function ($join) {
            $join->on("ae.id_almacen", "=", "vd.id_almacen")
                ->on("ae.id_producto", "=", "vd.id_producto");
        });

        $query->select(
            "p.codigo_producto",
            "p.descripcion",
            "p.factor_conversion",
            "p.id_producto",
            "p.id_linea",
            "p.id_familia",
            "v.id_sucursal",
            "a.id_almacen",
            "a.almacen",
            "ae.existencia",
            \DB::raw("(SUM(vd.cantidad) -  IFNULL((select SUM(dd.cantidad) from devoluciones_descripcion as dd LEFT JOIN devoluciones as d on (dd.id_devolucion = d.id_devolucion) where dd.id_producto = vd.id_producto and dd.id_almacen = vd.id_almacen and d.estatus_devolucion in ('Pagada') and DATE(d.fecha) >= DATE_SUB(CURDATE(),INTERVAL f.dias_anteriores_analisis day) and DATE(d.fecha) <= CURDATE()),0)) as venta")
        );

        $query->where("p.id_familia", "<>", 18);

        #$query->where(\DB::raw("date(v.fecha)"), "<=", \DB::raw("curdate()"));
        $query->where(\DB::raw("date(v.fecha)"), "<=", \DB::raw("CURDATE()"));

        #$query->where(\DB::raw("date(v.fecha)"), ">=", \DB::raw("date_sub(curdate(),interval f.dias_anteriores_analisis day)"));
        $query->where(\DB::raw("date(v.fecha)"), ">=", \DB::raw("date_sub(CURDATE(),interval f.dias_anteriores_analisis day)"));

        $query->where("v.atipica", "=", "no");

        $query->whereNotNull("vd.id_almacen");
        #$query->where("vd.id_almacen", 14);

        $query->where("vd.id_producto", $id_producto);

        $query->groupBy("p.id_producto");

//        $q = $query->toSql();
//        \Log::debug($q);
//        throw new \Exception("AQUI");

        return $query->get();
    }

    public function consultaVentas()
    {

        $query = $this->from("ventas_descripcion as vd");
        $query->leftJoin("ventas as v", "v.id_venta", "=", "vd.id_venta");
        $query->leftJoin("productos as p", "p.id_producto", "=", "vd.id_producto");
        $query->leftJoin("informacion_formatos as f", function ($join) {
            $join->where("f.id_informacion", "=", 1);
        });

        $query->select(
            "vp.*",
            "p.codigo_producto",
            "p.descripcion",
            "p.factor_conversion"
        );

        $query->where("p.id_familia", "<>", 18);

        #$query->where(\DB::raw("date(v.fecha)"), "<=", \DB::raw("curdate()"));
        $query->where(\DB::raw("date(v.fecha)"), "<=", \DB::raw("CURDATE()"));

        #$query->where(\DB::raw("date(v.fecha)"), ">=", \DB::raw("date_sub(curdate(),interval f.dias_anteriores_analisis day)"));
        $query->where(\DB::raw("date(v.fecha)"), ">=", \DB::raw("date_sub(CURDATE(),interval f.dias_anteriores_analisis day)"));

        return $query->get()->toArray();
    }

    public function buscarUltimaVenta($datos)
    {

        $query = $this->from("ventas_descripcion as vd")->select(
            \DB::raw("ifnull(vd.fecha,'') as ultima_venta")
        );

        if (!empty($datos["id_producto"])) {

            $query = $query->where("vd.id_producto", $datos["id_producto"]);

        }

        if (!empty($datos["id_almacen"])) {

            $query = $query->where("vd.id_almacen", $datos["id_almacen"]);

        }

        $query = $query->orderBy("vd.fecha", "desc");

        $query->limit(1);

        return $query->first();

    }

    public function buscarPartidas($datos)
    {

        $query = $this->from("ventas_descripcion as vd");
        $query->leftJoin("productos as p", "p.id_producto", "=", "vd.id_producto");

        $query->select(
            "vd.*",
            "p.codigo_producto",
            "p.descripcion"
        );

        if (!empty($datos["id_venta"])) {

            $query->where("vd.id_venta", $datos["id_venta"]);

        }

        return $query->get();

    }

    public function ventasAtipicas($parametros)
    {
        $query = $this->from('ventas_descripcion AS vd')
            ->select(
                'vd.id_venta',
                'cs.nombre AS sucursal',
                'vd.cantidad',
                'p.codigo_producto',
                'p.descripcion',
                'vps.promedio_porcentaje AS promedio_venta'
            )
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'vd.id_sucursal')
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'vd.id_producto')
            ->leftJoin('ventas_promedios_sucursales AS vps', function ($join) {
                $join->on('vps.producto_id', '=', 'vd.id_producto')
                    ->on('vps.sucursal_id', '=', 'vd.id_sucursal');
            });

        if (isset($parametros['fecha_inicio']) && $parametros['fecha_inicio'] != "") {
            $query = $query->where(\DB::raw('DATE(fecha)'), '>=', $parametros['fecha_inicio']);
        }

        if (isset($parametros['fecha_termino']) && $parametros['fecha_termino'] != "") {
            $query = $query->where(\DB::raw('DATE(fecha)'), '<=', $parametros['fecha_termino']);
        }

        if (isset($parametros['sucursal_id']) && !empty($parametros['sucursal_id'])) {
            $query = $query->whereIn('sucursal_id', $parametros['sucursal_id']);
        }

        if (isset($parametros['familia_id']) && !empty($parametros['familia_id'])) {
            $query = $query->whereIn('p.id_familia', $parametros['familia_id']);
        }

        if (isset($parametros['linea_id']) && !empty($parametros['linea_id'])) {
            $query = $query->whereIn('p.id_linea', $parametros['linea_id']);
        }

        if (isset($parametros['categoria_id']) && !empty($parametros['categoria_id'])) {
            $query = $query->whereIn('p.id_categoria', $parametros['categoria_id']);
        }

        if (isset($parametros['producto_id']) && !empty($parametros['producto_id'])) {
            $query = $query->where('p.id_producto', $parametros['producto_id']);
        }

        return $query->where('ventaAtipica', 'si')
            ->get();
    }

}