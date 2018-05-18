<?php

namespace App\Model\Finanzas;

use Illuminate\Database\Eloquent\Model;

class VentasDescripcion extends Model
{
    protected $table = 'agr_ventas_descripcion';

    protected $fillable = [
        'id',
        'venta_id',
        'cliente_id',
        'animal_id',
        'peso_animal',
        'precio_kilo',
        'importe_animal'
    ];

    public function reporte($datos)
    {

        $query = $this->leftJoin('agr_cliente as c', 'c.id', '=', 'agr_ventas_descripcion.cliente_id');
        $query->leftJoin('agr_animal as a', 'a.id', '=', 'agr_ventas_descripcion.animal_id');
        $query->leftJoin('agr_ventas as v', 'v.id', '=', 'agr_ventas_descripcion.venta_id');

        $query->select(
            'agr_ventas_descripcion.*',
            'a.numero',
            'c.nombre'
        );

        if (!empty($datos['venta_id'])) {
            $query->where('agr_ventas_descripcion.venta_id', $datos['venta_id']);
        }
        if (!empty($datos['animal_id'])) {
            $query->where('agr_ventas_descripcion.animal_id', $datos['animal_id']);
        }
        if (!empty($datos['estatus'])) {
            $query->whereNotIn('v.estatus', ['cancelado']);
        }

        //dd($query->toSql());
        return $query->get();

    }

    public function bono($datos)
    {

        $query = $this->leftJoin("agr_animal as a", "a.id", "=", "agr_ventas_descripcion.animal_id");
        $query->leftJoin("agr_potrero as p", "p.id", "=", "a.potrero_id");
        $query->leftJoin("agr_rancho as r", "r.id", "=", "p.rancho_id");
        $query->leftJoin("agr_ventas as v", "v.id", "=", "agr_ventas_descripcion.venta_id");

        $query->select(
            "r.id",
            "r.rancho",
            \DB::raw("sum(importe_animal) as total_rancho"),
            \DB::raw("sum(peso_animal) as peso_rancho")
        );

        $query->where("v.fecha", ">=", $datos["fecha_ini"]);
        $query->where("v.fecha", "<=", $datos["fecha_fin"]);
        $query->where("v.estatus", "validado");
        $query->groupBy("r.id");

        return $query->get();

    }

    public function ventasComprasFamilia($datos)
    {

        $query = $this->from('ventas_descripcion AS vd')
            ->select(
                \DB::raw('IFNULL(SUM(vd.cantidad * vd.precio), 0) AS total_ventas'),
                \DB::raw('(SELECT IFNULL(SUM(dd.sin_iva * (dd.cantidad)), 0) AS total_devoluciones FROM devoluciones_descripcion AS dd LEFT JOIN devoluciones AS d ON d.id_devolucion = dd.id_devolucion LEFT JOIN productos AS p2 ON p2.id_producto = dd.id_producto WHERE (d.estatus IN( "Pagada", "Pagada con Credito")) AND p.id_familia = p2.id_familia ' . (isset($datos['sucursal_id']) ? 'AND d.id_sucursal = v.id_sucursal' : "") . ' AND (DATE(d.fecha) >= "' . $datos['fecha_inicial'] . '" AND DATE(d.fecha) <= "' . $datos['fecha_final'] . '")) AS devoluciones'),
                \DB::raw('(SELECT IFNULL(SUM(aeod.cantidad * od.precio),0) AS compras FROM almacenes_entradas_ordenes_descripcion AS aeod LEFT JOIN compras_ordenes_descripcion AS od ON od.id_orden_descripcion=aeod.id_orden_descripcion LEFT JOIN almacenes_entradas_ordenes AS aeo ON aeod.id_entrada_orden=aeo.id_entrada_orden LEFT JOIN productos AS p2 ON p2.id_producto=aeod.id_producto WHERE p.id_familia=p2.id_familia ' . (isset($datos['sucursal_id']) ? 'AND aeod.id_sucursal = v.id_sucursal' : 'AND aeod.id_sucursal IN(2,3,4,5,6,7,8,13,19)') . ' AND (DATE(aeo.fecha_entrada) >= "' . $datos['fecha_inicial'] . '" AND DATE(aeo.fecha_entrada) <= "' . $datos['fecha_final'] . '")) AS compras'),
                'pf.familia',
                'pf.id_familia',
                'pl.linea',
                'cs.nombre AS sucursal',
                'cs.id_sucursal'
            )
            ->leftJoin('ventas AS v', 'v.id_venta', '=', 'vd.id_venta')
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'vd.id_producto')
            ->leftJoin('productos_familias AS pf', 'pf.id_familia', '=', 'p.id_familia')
            ->leftJoin('productos_lineas AS pl', 'pl.id_linea', '=', 'p.id_linea')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'v.id_sucursal')
            ->whereBetween(\DB::raw('DATE(v.fecha)'), [$datos['fecha_inicial'], $datos['fecha_final']]);

        if (isset($datos['sucursal_id'])) {
            $query = $query->where('v.id_sucursal', $datos['sucursal_id']);
        }

        return $query->groupBy('p.id_familia')
            ->orderBy('total_ventas', 'DESC')
            ->get();
    }

    public function ventasComprasLinea($datos)
    {

        $query = $this->from('ventas_descripcion AS vd')
            ->select(
                \DB::raw('IFNULL(SUM(vd.cantidad * vd.precio), 0) AS total_ventas'),
                \DB::raw('(SELECT IFNULL(SUM(dd.sin_iva * (dd.cantidad)), 0) AS total_devoluciones FROM devoluciones_descripcion AS dd LEFT JOIN devoluciones AS d ON d.id_devolucion = dd.id_devolucion LEFT JOIN productos AS p2 ON p2.id_producto = dd.id_producto WHERE (d.estatus IN( "Pagada", "Pagada con Credito")) AND p.id_linea = p2.id_linea ' . (isset($datos['sucursal_id']) ? 'AND d.id_sucursal = v.id_sucursal' : "") . ' AND (DATE(d.fecha) >= "' . $datos['fecha_inicial'] . '" AND DATE(d.fecha) <= "' . $datos['fecha_inicial'] . '")) AS devoluciones'),
                \DB::raw('(SELECT IFNULL(SUM(aeod.cantidad * od.precio),0) AS compras FROM almacenes_entradas_ordenes_descripcion AS aeod LEFT JOIN compras_ordenes_descripcion AS od ON od.id_orden_descripcion=aeod.id_orden_descripcion LEFT JOIN almacenes_entradas_ordenes AS aeo ON aeod.id_entrada_orden=aeo.id_entrada_orden LEFT JOIN productos AS p2 ON p2.id_producto=aeod.id_producto WHERE p.id_linea=p2.id_linea ' . (isset($datos['sucursal_id']) ? 'AND aeod.id_sucursal = v.id_sucursal' : 'AND aeod.id_sucursal IN(2,3,4,5,6,7,8,13,19)') . ' AND (DATE(aeo.fecha_entrada) >= "' . $datos['fecha_inicial'] . '" AND DATE(aeo.fecha_entrada) <= "' . $datos['fecha_final'] . '")) AS compras'),
                'pf.familia',
                'pf.id_familia',
                'pl.linea',
                'pl.id_linea',
                'cs.nombre AS sucursal',
                'cs.id_sucursal'
            )
            ->leftJoin('ventas AS v', 'v.id_venta', '=', 'vd.id_venta')
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'vd.id_producto')
            ->leftJoin('productos_familias AS pf', 'pf.id_familia', '=', 'p.id_familia')
            ->leftJoin('productos_lineas AS pl', 'pl.id_linea', '=', 'p.id_linea')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'v.id_sucursal')
            ->whereBetween(\DB::raw('DATE(v.fecha)'), [$datos['fecha_inicial'], $datos['fecha_final']]);

        if (isset($datos['sucursal_id'])) {
            $query = $query->where('v.id_sucursal', $datos['sucursal_id']);
        }

        return $query->groupBy('p.id_linea')
            ->orderBy('total_ventas', 'DESC')
            ->get();
    }

    public function ventasComprasProducto($datos)
    {
        $query = $this->from('ventas_descripcion AS vd')
            ->select(
                \DB::raw('IFNULL(SUM(vd.cantidad * vd.precio), 0) AS total_ventas'),
                \DB::raw('(SELECT IFNULL(SUM(dd.sin_iva * (dd.cantidad)), 0) AS total_devoluciones FROM devoluciones_descripcion AS dd LEFT JOIN devoluciones AS d ON d.id_devolucion = dd.id_devolucion LEFT JOIN productos AS p2 ON p2.id_producto = dd.id_producto WHERE (d.estatus IN( "Pagada", "Pagada con Credito")) '.(isset($datos['familia_id']) ? 'AND p.id_familia = p2.id_familia' : "").(isset($datos['linea_id']) ? 'AND p.id_linea = p2.id_linea' : "").' AND (DATE(d.fecha) >= "' . $datos['fecha_inicial'] . '" AND DATE(d.fecha) <= "' . $datos['fecha_final'] . '") AND p2.id_producto = p.id_producto '.(isset($datos['sucursal_id']) ? 'AND d.id_sucursal = v.id_sucursal' : "").') AS devoluciones'),
                \DB::raw('(SELECT IFNULL(SUM(aeod.cantidad * od.precio),0) AS compras FROM almacenes_entradas_ordenes_descripcion AS aeod LEFT JOIN compras_ordenes_descripcion AS od ON od.id_orden_descripcion=aeod.id_orden_descripcion LEFT JOIN almacenes_entradas_ordenes AS aeo ON aeod.id_entrada_orden=aeo.id_entrada_orden LEFT JOIN productos AS p2 ON p2.id_producto=aeod.id_producto WHERE (DATE(aeo.fecha_entrada) >= "' . $datos['fecha_inicial'] . '" AND DATE(aeo.fecha_entrada) <= "' . $datos['fecha_final'] . '")  '.(isset($datos['familia_id']) ? ' AND p.id_familia = p2.id_familia' : "").(isset($datos['sucursal_id']) ? ' AND aeod.id_sucursal = v.id_sucursal' : "").(isset($datos['linea_id']) ? ' AND p.id_linea = p2.id_linea' : "").' AND p2.id_producto = p.id_producto) AS compras'),
                'pf.familia',
                'pf.id_familia',
                'pl.linea',
                'cs.nombre AS sucursal',
                'cs.id_sucursal',
                'p.codigo_producto',
                'p.descripcion'
            )
            ->leftJoin('ventas AS v', 'v.id_venta', '=', 'vd.id_venta')
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'vd.id_producto')
            ->leftJoin('productos_familias AS pf', 'pf.id_familia', '=', 'p.id_familia')
            ->leftJoin('productos_lineas AS pl', 'pl.id_linea', '=', 'p.id_linea')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'v.id_sucursal')
            ->whereBetween(\DB::raw('DATE(v.fecha)'), [$datos['fecha_inicial'], $datos['fecha_final']]);

        if (isset($datos['sucursal_id'])) {
            $query = $query->where('v.id_sucursal', $datos['sucursal_id']);
        }

        if (!empty($datos['familia_id'])) {
            $query = $query->where('p.id_familia', $datos['familia_id'])
                ->groupBy(['p.id_familia', 'p.id_producto']);
        }

        if (!empty($datos['linea_id'])) {
            $query = $query->where('p.id_linea', $datos['linea_id'])
                ->groupBy(['p.id_linea', 'p.id_producto']);
        }

        return $query->orderBy('total_ventas', 'DESC')
            ->get();
    }
}