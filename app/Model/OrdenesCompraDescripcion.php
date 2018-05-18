<?php

namespace App\Model;

use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model;

class OrdenesCompraDescripcion extends Model
{
    protected $table = 'compras_ordenes_descripcion';
    protected $primaryKey = "id_orden_descripcion";

    protected $fillable = [
        'id_orden',
        'id_cotizacion',
        'id_solicitud_cotizacion',
        'id_proveedor',
        'id_solicitud',
        'id_sucursal',
        'id_almacen',
        'id_producto',
        'cantidad',
        'precio_lista',
        'precio',
        'cantidad_respaldo',
        'precio_respaldo',
        'pronto_pago',
        'porcentaje_iva',
        'estatus',
        'embarque',
        'partida_nueva',
        'fecha_probable_entrega',
        'fecha_ultima_entrega',
        'variacion_costo',
        'porcentaje_variacion'
    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin('compras_ordenes as co', 'co.id_orden', '=', 'compras_ordenes_descripcion.id_orden');
        $query->leftJoin('productos as p', 'p.id_producto', '=', 'compras_ordenes_descripcion.id_producto');
        $query->leftJoin('productos_unidades_medida as un', 'un.id_unidad_medida', '=', 'p.unidad_compra');
        $query->leftJoin('almacenes as a', 'a.id_almacen', '=', 'compras_ordenes_descripcion.id_almacen');
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "compras_ordenes_descripcion.id_sucursal");
        $query->leftJoin('iva', 'iva.id_iva', '=', 'p.id_iva');
        $query->leftJoin('almacenes_entradas_ordenes_backorders as b', function ($join) use ($datos) {
            $join->on("b.id_producto", "=", "compras_ordenes_descripcion.id_producto")
                ->on("b.id_orden", "=", "co.id_orden");
        });
        $query->leftJoin('almacenes_existencias as ax', function ($join) {
            $join->on("ax.id_producto", "=", "p.id_producto");
            $join->on("ax.id_almacen", "=", "compras_ordenes_descripcion.id_almacen");

        });
        $query->leftJoin("compras_ordenes_ventas as cov", "cov.id_orden_descripcion", "=", "compras_ordenes_descripcion.id_orden_descripcion")
            ->leftJoin('productos_proveedores_precios_lista AS pppl', function ($join) use ($datos) {
                $join->on("pppl.proveedor_id", "=", "compras_ordenes_descripcion.id_proveedor")
                    ->on("pppl.producto_id", "=", "compras_ordenes_descripcion.id_producto");
            });

        $query->select(
            'compras_ordenes_descripcion.*',
            'p.codigo_producto',
            'p.descripcion',
            'un.unidad_medida',
            'a.almacen',
            'b.cantidad as backorder',
            's.nombre as sucursal',
            "p.clasificacion",
            "iva.porcentaje",
            \DB::raw('ifnull(p.peso,0) as peso_producto'),
            \DB::raw('obtener_entradas_ordenes(compras_ordenes_descripcion.id_orden_descripcion) as entradas'),
            \DB::raw('ifnull(compras_ordenes_descripcion.cantidad_respaldo,0) as c_respaldo'),
            \DB::raw('ifnull(compras_ordenes_descripcion.precio_respaldo,0) as p_respaldo'),
            \DB::raw('ifnull(ax.existencia,0) as existencia'),
            \DB::raw('compras_ordenes_pagos(compras_ordenes_descripcion.id_orden_descripcion) as pagos'),
            \DB::raw("IFNULL(pppl.precio_lista,0) as precio_lista"),
            \DB::raw('(select ifnull(precio_costo,0) from productos_historial_costos as phc WHERE phc.id_producto = p.id_producto order by id_historial_costo desc limit 1) as upc'),
            \DB::raw('compras_ordenes_descripcion.cantidad - comprasOrdenesBackorder(compras_ordenes_descripcion.id_orden_descripcion) as backorder'),
            \DB::raw('ifnull(p.codigo_truper,"") as codigo_truper'),
            \DB::raw('ifnull(cov.id,"") as compra_venta')
        );

        if (!empty($datos['id_orden_descripcion'])) {
            $query->where('compras_ordenes_descripcion.id_orden_descripcion', $datos['id_orden_descripcion']);
            return $query->first();
        }

        if (!empty($datos['id_orden'])) {
            $query->where('compras_ordenes_descripcion.id_orden', $datos['id_orden']);
        }

        if (!empty($datos["ordenar"])) {
            $query->orderBy("p.codigo_truper");
        }

        if (!empty($datos["id_sucursal"])) {

            $query->where("compras_ordenes_descripcion.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["id_almacen"])) {

            $query->where("compras_ordenes_descripcion.id_almacen", $datos["id_almacen"]);

        }

        if (!empty($datos["id_producto"])) {

            $query->where("compras_ordenes_descripcion.id_producto", $datos["id_producto"]);

        }

        if (!empty($datos["verEstatus"])) {

            $query->where("compras_ordenes_descripcion.estatus", "<>", "finalizada");
            $query->where("compras_ordenes_descripcion.estatus", "<>", "cancelada");
            $query->where("compras_ordenes_descripcion.cantidad", ">", "0");

        }

        if (!empty($datos["first"])) {

            return $query->first();

        }

        $query->groupBy("p.id_producto");

        //dd($query->toSql());

        return $query->get();

    }

    public function buscarPDF($datos)
    {
        $query = $this->leftJoin('compras_ordenes as co', 'co.id_orden', '=', 'compras_ordenes_descripcion.id_orden');
        $query->leftJoin('productos as p', 'p.id_producto', '=', 'compras_ordenes_descripcion.id_producto');
        $query->leftJoin('productos_unidades_medida as un', 'un.id_unidad_medida', '=', 'p.unidad_compra');
        $query->leftJoin('productos_unidades_medida as uv', 'uv.id_unidad_medida', '=', 'p.unidad_venta');
        $query->leftJoin('almacenes as a', 'a.id_almacen', '=', 'compras_ordenes_descripcion.id_almacen');
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "compras_ordenes_descripcion.id_sucursal");
        $query->leftJoin('almacenes_entradas_ordenes_backorders as b', function ($join) use ($datos) {
            $join->on("b.id_producto", "=", "compras_ordenes_descripcion.id_producto")
                ->where("b.id_orden", "=", $datos['id_orden']);
        });
            /*->leftJoin('productos_proveedores_precios_lista AS pppl', function ($join) use ($datos) {
                $join->on("pppl.proveedor_id", "=", "compras_ordenes_descripcion.id_proveedor")
                    ->on("pppl.producto_id", "=", "compras_ordenes_descripcion.id_producto");
            });*/

        $query->select(
            'compras_ordenes_descripcion.*',
            \DB::raw("IFNULL(compras_ordenes_descripcion.precio_lista,0) as precio_lista"),
            'p.codigo_producto',
            'p.descripcion',
            'p.factor_conversion',
            'un.unidad_medida',
            'uv.unidad_medida as unidad_venta',
            'a.almacen',
            'b.cantidad as backorder',
            \DB::raw('ifnull(p.codigo_truper, 0) as codigo_truper')
        );

        if (!empty($datos['id_orden'])) {
            $query->where('compras_ordenes_descripcion.id_orden', $datos['id_orden']);
        }

        $query->groupBy("compras_ordenes_descripcion.id_orden_descripcion");

        //dd($query->toSql());

        return $query->get();

    }

    public function totales($datos)
    {

        $query = $this->leftJoin('productos as p', 'p.id_producto', '=', 'compras_ordenes_descripcion.id_producto');
        $query->leftJoin('iva', 'iva.id_iva', '=', 'p.id_iva');

        $query->select(
            \DB::raw('SUM((cantidad * precio)*(((iva.porcentaje/100)+1))) AS total')
        );

        if (!empty($datos['id_orden'])) {
            $query->where('id_orden', $datos['id_orden']);
            return $query->first();
        }
    }

    public function porProgramar($datos)
    {

        $query = $this->from("compras_ordenes_descripcion as cod");
        $query->leftJoin("compras_ordenes as co", "co.id_orden", "=", "cod.id_orden");
        $query->leftJoin("cat_proveedores as cp", "cp.id_proveedor", "=", "co.id_proveedor");
        $query->leftJoin("cat_sucursales as cs", "cs.id_sucursal", "=", "co.id_sucursal");

        if (isset($datos["id_proveedor"])) {

            $query->leftJoin("almacenes_entradas_ordenes as aeo", "aeo.id_orden", "=", "co.id_orden");

            $select = [
                "co.id_orden",
                "co.estatus",
                \DB::raw("ifnull(co.fecha_autorizacion,'') as fecha_autorizacion"),
                "aeo.fecha_entrada",
                \DB::raw("IFNULL(SUM((cod.cantidad*cod.precio)*1.16),0) AS importe")
            ];

        } else {

            $select = [
                \DB::raw("ifnull(sum((ifnull((SELECT sum(aeod.cantidad) FROM almacenes_entradas_ordenes_descripcion AS aeod WHERE aeod.id_orden_descripcion = cod.id_orden_descripcion),0) - ifnull((SELECT sum(cofd.cantidad) FROM compras_ordenes_facturas_descripcion AS cofd LEFT JOIN compras_ordenes_facturas AS cof ON cof.id_orden_factura = cofd.id_orden_factura WHERE cofd.id_orden_descripcion = cod.id_orden_descripcion AND cof.estatus IN ('pendiente', 'pagada')),0)) * (cod.precio * p_iva (cod.id_producto))),0) AS `por_programar`"),
                "cp.nombre as proveedor",
                "cp.id_proveedor",
                "cs.nombre as sucursal",
                "cs.id_sucursal"
            ];

        }

        $query->select($select);

        $query->whereNotIn("co.estatus", ["proceso", "cancelada", "ignorar"]);
        $query->whereNotIn("cod.estatus", ["pe", "cancelada", "ignorar", "autorizacion"]);
        $query->where(\DB::raw("date(co.fecha_orden)"), ">=", $datos["fecha_inicio"]);
        $query->where("co.programacion_pagos", "<>", "no");
        $query->where(\DB::raw("ifnull((SELECT sum(ifnull(e.cantidad, 0)) FROM almacenes_entradas_ordenes_descripcion AS e WHERE cod.id_orden_descripcion = e.id_orden_descripcion),0)"), ">", \DB::raw("ifnull((SELECT sum(f.cantidad) FROM compras_ordenes_facturas_descripcion AS f LEFT JOIN compras_ordenes_facturas AS fp ON (f.id_orden_factura = fp.id_orden_factura) WHERE cod.id_orden_descripcion = f.id_orden_descripcion AND fp.estatus IN ('pendiente', 'pagada')),0)"));

        if (!empty($datos["id_sucursal"])) {

            $query->where("co.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["id_proveedor"])) {

            $query->where("co.id_proveedor", $datos["id_proveedor"]);
            $query->groupBy("co.id_orden");
            $query->orderBy("importe", "desc");

        } else {

            $query->groupBy("co.id_proveedor");
            $query->orderBy("por_programar", "desc");

        }

        //dd($query->toSql());
        return $query->get();

    }

    public function porLlegar($datos)
    {

        $query = $this->from("compras_ordenes_descripcion as cod");
        $query->leftJoin("compras_ordenes as co", "co.id_orden", "=", "cod.id_orden");
        $query->leftJoin("cat_proveedores as cp", "cp.id_proveedor", "=", "co.id_proveedor");
        $query->leftJoin("cat_sucursales as cs", "cs.id_sucursal", "=", "co.id_sucursal");

        if (isset($datos["id_proveedor"])) {

            $select = [
                "co.id_orden",
                "co.estatus",
                \DB::raw("ifnull(co.fecha_autorizacion,'') as fecha_autorizacion"),
                \DB::raw("IFNULL(SUM((cod.cantidad*cod.precio)*1.16),0) AS importe")
            ];

        } else {

            $select = [
                \DB::raw("ifnull(sum((ifnull(cod.cantidad, 0) - ifnull((SELECT sum(cofd.cantidad) FROM compras_ordenes_facturas_descripcion AS cofd LEFT JOIN compras_ordenes_facturas AS cof ON cof.id_orden_factura = cofd.id_orden_factura WHERE cofd.id_orden_descripcion = cod.id_orden_descripcion AND cof.estatus IN ('pendiente', 'pagada')),0)) * (cod.precio * p_iva (cod.id_producto))),0) AS por_llegar"),
                "cp.nombre as proveedor",
                "cp.id_proveedor",
                "cs.nombre as sucursal",
                "cs.id_sucursal"
            ];

        }

        $query->select($select);

        if (!empty($datos["id_sucursal"])) {

            $query->where("co.id_sucursal", $datos["id_sucursal"]);

        }

        $query->whereIn("co.estatus", ["autorizada", "procesa"]);
        $query->where(\DB::raw("date(co.fecha_orden)"), ">=", $datos["fecha_inicio"]);

        if (!empty($datos["id_proveedor"])) {

            $query->where("co.id_proveedor", $datos["id_proveedor"]);
            $query->groupBy("co.id_orden");
            $query->orderBy("importe", "desc");

        } else {

            $query->groupBy("co.id_proveedor");
            $query->orderBy("por_llegar", "desc");

        }

        //dd($query->toSql());
        return $query->get();

    }

    public function obtenerOrdenesCompra($id_producto, $id_almacen)
    {

        $query = $this->from("compras_ordenes_descripcion as cod");
        $query->leftJoin("compras_ordenes as c", "c.id_orden", "=", "cod.id_orden");

        $query->select(
            \DB::raw("(sum(cod.cantidad)-IFNULL((select sum(ae.cantidad) from almacenes_entradas_ordenes_descripcion as ae where cod.id_orden_descripcion = ae.id_orden_descripcion),0)) as compras")
        );

        $query->where("cod.id_producto", $id_producto);
        $query->where("cod.id_almacen", $id_almacen);
        $query->whereNotIn("cod.estatus", ["entregado", "cancelada", "finalizada"]);
        $query->where("c.compra_especial", "=", 0);

        return $query->first();

    }
    
    public function buscarPrecioLista($datos)
    {
        
        $query = $this->from("compras_ordenes_descripcion as cod");
        $query->leftJoin("compras_ordenes as co", "co.id_orden", "=", "cod.id_orden");

        $query->select(
            \DB::raw("ifnull(cod.precio_lista,0) as precio_lista")
        );

        if (!empty($datos["id_sucursal"])){

            $query->where("cod.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["id_almacen"])){

            $query->where("cod.id_almacen", $datos["id_almacen"]);

        }

        if (!empty($datos["id_producto"])){

            $query->where("cod.id_producto", $datos["id_producto"]);

        }

        $query->where("cod.precio_lista", "!=", 0);

        $query->orderBy("co.fecha_orden", "desc");

        $query->limit(1);

        //dd($query->toSql());

        return $query->first();
        
    }

}