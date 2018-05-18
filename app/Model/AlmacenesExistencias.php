<?php

namespace App\Model;

use App\Http\Requests\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AlmacenesExistencias extends Model
{
    protected $table = "almacenes_existencias";

    protected $primaryKey = 'id_existencia';

    protected $connection = "mysql";

    protected $fillable = [
        'id_sucursal',
        'id_almacen',
        'id_familia',
        'id_categoria',
        'id_linea',
        'id_producto',
        'existencia',
        'stock_minimo',
        'stock_maximo',
        'minimo_compra',
        'maximo_compra',
        'codigo_almacen',
        'codigo_producto',
        'conteo',
        'precio_costo'
    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin('almacenes as a', 'a.id_almacen', '=', 'almacenes_existencias.id_almacen');

        $query->select(
            'almacenes_existencias.id_sucursal',
            'a.almacen',
            'almacenes_existencias.id_almacen',
            'almacenes_existencias.id_producto',
            'a.bandera',
            'almacenes_existencias.existencia'
        );

        if (!empty($datos['id_sucursal'])) {
            $query = $query->where('almacenes_existencias.id_sucursal', $datos['id_sucursal']);
        }

        if (isset($datos['bandera'])) {
            $query = $query->where('a.bandera', $datos['bandera']);
        }

        if (!empty($datos['id_producto'])) {
            $query = $query->where('id_producto', $datos['id_producto']);
        }

        if (!empty($datos['ordenar_desc'])) {
            $query = $query->orderBy($datos['ordenar_desc'], 'DESC');
        }

        if (!empty($datos['first'])) {
            if ($datos['first']) {
                return $query->first();
            }
        }

        return $query->get();
    }

    public function buscarAlmacenes($datos)
    {
        $query = $this->leftJoin('almacenes as a', 'a.id_almacen', '=', 'almacenes_existencias.id_almacen');
//        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "almacenes_existencias.id_almacen");
        $query->leftJoin("cat_sucursales as cs", "cs.id_sucursal", "=", "a.id_sucursal");
        $query->leftJoin("productos as p", "p.id_producto", "=", "almacenes_existencias.id_producto");
        $query->leftJoin("productos_categorias as c", "c.id_categoria", "=", "almacenes_existencias.id_categoria");
        $query->leftJoin("productos_familias as f", "f.id_familia", "=", "almacenes_existencias.id_familia");
        $query->leftJoin("productos_lineas as l", "l.id_linea", "=", "almacenes_existencias.id_linea");


        $select = [
            'p.factor_conversion',
            'p.codigo_producto',
            'p.descripcion',
            'almacenes_existencias.id_sucursal',
            'almacenes_existencias.existencia',
            'almacenes_existencias.id_almacen',
            'almacenes_existencias.id_existencia',
            'almacenes_existencias.id_familia',
            'almacenes_existencias.id_linea',
            'almacenes_existencias.id_categoria',
            'a.almacen',
            'almacenes_existencias.id_producto',
            'a.bandera',
            \DB::raw("ifnull(almacenes_existencias.stock_minimo,0) as stock_minimo"),
            \DB::raw("ifnull(almacenes_existencias.stock_maximo,0) as stock_maximo"),
            "cs.nombre as sucursal",
            "c.categoria",
            "f.familia",
            "l.linea",
            "almacenes_existencias.rotacion"
        ];

        $query->select(
            $select
        );

        if (!empty($datos['id_producto'])) {
            $query->where('almacenes_existencias.id_producto', $datos['id_producto']);
        }
        if (!empty($datos['id_sucursal'])) {
            $query->where('almacenes_existencias.id_sucursal', $datos['id_sucursal']);
        }

        if (!empty($datos['bandera'])) {
            $query->where('a.bandera', $datos['bandera']);
        }

        if (!empty($datos["id_almacen"])) {

            if (count($datos["id_almacen"]) > 1) {

                $query->whereIn("almacenes_existencias.id_almacen", $datos["id_almacen"]);

            } else {

                $query->where("almacenes_existencias.id_almacen", $datos["id_almacen"]);

            }

        }

        if (!empty($datos["id_familia"])) {

            if (count($datos["id_familia"]) > 1) {

                $query->whereIn("almacenes_existencias.id_familia", $datos["id_familia"]);

            } else {

                $query->where("almacenes_existencias.id_familia", $datos["id_familia"]);

            }

        }

        if (!empty($datos["id_linea"])) {

            if (count($datos["id_linea"]) > 1) {

                $query->whereIn("almacenes_existencias.id_linea", $datos["id_linea"]);

            } else {

                $query->where("almacenes_existencias.id_linea", $datos["id_linea"]);

            }

        }

        if (!empty($datos["id_categoria"])) {

            if (count($datos["id_categoria"]) > 1) {

                $query->whereIn("almacenes_existencias.id_categoria", $datos["id_categoria"]);

            } else {

                $query->where("almacenes_existencias.id_categoria", $datos["id_categoria"]);

            }

        }

        if (!empty($datos["first"])) {
            return $query->first();
        }

        //dd($query->toSql());

        return $query->get();
    }

    public function buscarPorPrecio($datos)
    {

        if ($datos["tipo_venta_id"] != "") {
            $tipo = $datos["tipo_venta_id"];
            $campo = "tipo_venta_id";
            $campo_v = "id_tipo_venta";
        } else {
            $tipo = $datos["tipo_precio_id"];
            $campo = "tipo_precio_id";
            $campo_v = "tipo_precio_id";
        }

        $query = $this->leftJoin('almacenes as a', 'a.id_almacen', '=', 'almacenes_existencias.id_almacen');
        $query->leftJoin("productos as p", "p.id_producto", "=", "almacenes_existencias.id_producto");
        $query->leftJoin("iva", "iva.id_iva", "=", "p.id_iva");

        $query->leftJoin("productos_porcentaje_utilidad as pu", function ($join) use ($tipo, $campo) {
            $join->on("pu.producto_id", "=", "p.id_producto")
                ->on("pu.sucursal_id", "=", "almacenes_existencias.id_sucursal")
                ->where("pu." . $campo, "=", $tipo);
        });

        $query->leftJoin("productos_lineas as l", "l.id_linea", "=", "p.id_linea");
        $query->leftJoin("productos_lineas_porcentaje_utilidad as lu", function ($join) use ($tipo, $campo) {
            $join->on("lu.linea_id", "=", "p.id_linea")
                ->on("lu.sucursal_id", "=", "almacenes_existencias.id_sucursal")
                ->where("lu." . $campo, "=", $tipo);
        });

        $query->leftJoin("productos_familias as f", "f.id_familia", "=", "p.id_familia");
        $query->leftJoin("productos_familias_porcentaje_utilidad as fu", function ($join) use ($tipo, $campo) {
            $join->on("fu.familia_id", "=", "p.id_familia")
                ->on("fu.sucursal_id", "=", "almacenes_existencias.id_sucursal")
                ->where("fu." . $campo, "=", $tipo);
        });

        $query->leftJoin("productos_sucursales_precios_venta as pv", function ($join) use ($tipo, $campo_v) {

            $join->on("pv.id_producto", "=", "p.id_producto")
                ->on("pv.id_sucursal", "=", "almacenes_existencias.id_sucursal")
                ->where("pv." . $campo_v, "=", $tipo);
        });

        $query->leftJoin("productos_sucursales_precio_base as pb", function ($join) {
            $join->on("pb.id_producto", "=", "p.id_producto")
                ->on("pb.id_sucursal", "=", "almacenes_existencias.id_sucursal");
        });

//        $query->whereNotNull("pu.id")->orWhere(function ($q) {
//            $q->whereNotNull("lu.id")->orWhere(function ($q2) {
//                $q2->whereNotNull("fu.id");
//
//            });
//        });

        $query->where(function ($q) {
            $q->whereNotNull("pu.id")
                ->orWhere(function ($q2) {
                    $q2->whereNotNull("lu.id")
                        ->orWhere(function ($q3) {

                            $q3->whereNotNull("fu.id");
                        });
                });

        });

        $query->whereNotNull("pv.id_productos_sucursales_precios_venta");


        $select = ['almacenes_existencias.id_sucursal',
            'almacenes_existencias.id_almacen',
            'almacenes_existencias.id_producto',
            "p.id_familia",
            "p.id_producto",
            "p.id_linea",
            "p.codigo_producto",
            "p.descripcion",
            "a.almacen as almacen",
            \DB::raw("ifnull(pu.porcentaje,0) as porcentaje_producto"),
            \DB::raw("ifnull(lu.porcentaje,0) as porcentaje_linea"),
            \DB::raw("ifnull(fu.porcentaje,0) as porcentaje_familia"),
            \DB::raw("ifnull(pb.precio,0) as costo"),
            \DB::raw("ifnull(pv.precio,0) as precio"),
            \DB::raw("ifnull(pv.precio,0) as precio_actual"),
            "iva.porcentaje as porcentaje_iva",
            'pv.id_productos_sucursales_precios_venta',];

//        $query->where("p.codigo_producto", "co140");

        /*if (isset($datos['linea_id'])) {

            $query->leftJoin("productos_lineas as l", "l.id_linea", "=", "p.id_linea");
            $query->leftJoin("productos_lineas_porcentaje_utilidad as lu", function ($join) use ($tipo, $campo) {
                $join->on("lu.linea_id", "=", "p.id_linea")
                    ->on("lu.sucursal_id", "=", "almacenes_existencias.id_sucursal")
                    ->where("lu.".$campo, "=", $tipo);
            });

            $select[] =

        }*/

        if (!empty($datos['producto_id'])) {
            $query->where('p.id_producto', $datos['producto_id']);
        } else {
            $query->where("p.actualizacion_precios", "SI");
        }

        if (!empty($datos['producto_ids'])) {
//            dd($datos["producto_ids"]);
            $query->whereIn('p.id_producto', $datos['producto_ids']);

        }
        if (!empty($datos['linea_id'])) {
            $query->where('p.id_linea', $datos['linea_id']);
        }

        if (!empty($datos['familia_id'])) {
            $query->where('p.id_familia', $datos['familia_id']);
        }
        if (!empty($datos['sucursal_id'])) {
            $query->where('almacenes_existencias.id_sucursal', $datos['sucursal_id']);
        }

        $query->select(
            $select
        );

        return $query->get();

    }

    public function buscarExistencias($datos)
    {

        $query = $this->leftJoin("productos as p", "p.id_producto", "=", "almacenes_existencias.id_producto");
        $query->leftJoin("productos_familias as f", "f.id_familia", "=", "p.id_familia");
        $query->leftJoin("productos_lineas as l", "l.id_linea", "=", "p.id_linea");
        $query->leftJoin("productos_categorias as c", "c.id_categoria", "=", "p.id_categoria");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "almacenes_existencias.id_sucursal");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "almacenes_existencias.id_almacen");

        $query->select(
            "almacenes_existencias.*",
            "p.codigo_producto as codigo_producto_actual",
            "p.descripcion",
            "p.factor_conversion",
            "s.nombre as sucursal",
            "a.almacen",
            \DB::raw("obtenerReservado(almacenes_existencias.id_producto, almacenes_existencias.id_almacen) as reservado"),
            \DB::raw("obtenerReservadoTransferencias(almacenes_existencias.id_producto, almacenes_existencias.id_almacen) as reservado_transferencia"),
            \DB::raw("ifnull(almacenes_existencias.existencia,0) as existencia"),
            \DB::raw("ifnull(almacenes_existencias.stock_minimo,0) as stock_minimo"),
            \DB::raw("ifnull(almacenes_existencias.stock_maximo,0) as stock_maximo"),
            "f.familia",
            "l.linea",
            "c.categoria"
        );

        if (!empty($datos["id_sucursal"])) {

            $query->whereIn("almacenes_existencias.id_sucursal", $datos["id_sucursal"]);

        }
        if (!empty($datos["producto"])) {

            $query->where("p.codigo_producto", "like", "%" . $datos["producto"] . "%");

        }
        if (!empty($datos["id_familia"])) {

            $query->whereIn("p.id_familia", $datos["id_familia"]);

        }
        if (!empty($datos["id_linea"])) {

            $query->whereIn("p.id_linea", $datos["id_linea"]);

        }
        if (!empty($datos["id_almacen"])) {

            if (count($datos["id_almacen"]) > 1) {

                $query->whereIn("almacenes_existencias.id_almacen", $datos["id_almacen"]);

            } else {

                $query->where("almacenes_existencias.id_almacen", $datos["id_almacen"]);

            }

        }
        if (!empty($datos["cero"])) {
            if ($datos["cero"] == "si") {

                $query->where("almacenes_existencias.existencia", "=", "0");

            }
        }
        if (!empty($datos["minimo"])) {
            if ($datos["minimo"] == "si") {

                $query->where("almacenes_existencias.existencia", "<", "almacenes_existencias.stock_minimo");

            }
        }
        if (!empty($datos["id_producto"])) {

            $query->where("almacenes_existencias.id_producto", $datos["id_producto"]);

        }
        if (!empty($datos["first"])) {

            return $query->first();

        }

        return $query->get();

    }

    /*
     * FUNCIÓN PARA ACTUALIZAR CONTEOS A 0 EN UNA SUCURSAL
     * Y CODIGOS DE PRODUCTOS SELECCIONADOS
     * */
    /**
     * @param $datos
     */
    public function actualizarConteo($datos)
    {

        $this->timestamps = false;
        $query = $this->leftJoin('productos AS p', 'p.id_producto', '=', 'almacenes_existencias.id_producto')
            ->where('almacenes_existencias.id_almacen', $datos['almacen_id']);
        if (!empty($datos['producto_id'])) {
            $query = $query->whereIn('p.codigo_producto', $datos['producto_id']);
        }
        $query->update([
            'almacenes_existencias.conteo' => 0,
            'almacenes_existencias.updated_at' => Carbon::now()
        ]);
    }

    public function diferenciaInventarios($datos)
    {
        $query = $this->select(
            'a.almacen',
            'almacenes_existencias.id_almacen',
            'almacenes_existencias.id_existencia',
            'p.codigo_producto',
            'p.descripcion',
            'p.id_producto',
            'almacenes_existencias.existencia',
            \DB::raw('ROUND((((ifnull(almacenes_existencias.existencia, 0)+ ifnull((
            SELECT sum(reservado) FROM almacenes_reservados AS ar
            WHERE ar.id_almacen = almacenes_existencias.id_almacen 
            AND ar.id_producto = almacenes_existencias.id_producto
            ),0) + ifnull((
            SELECT sum(cantidad) FROM almacenes_reservados_transferencias AS ar
            WHERE ar.almacen_origen = almacenes_existencias.id_almacen
            AND ar.id_producto = almacenes_existencias.id_producto
            ),0))) + ((ifnull((
            SELECT sum(sal.cantidad) FROM almacenes_salidas AS sal
            WHERE sal.id_producto = almacenes_existencias.id_producto
            AND sal.id_almacen = almacenes_existencias.id_almacen
            AND sal.fecha > "2011-06-03 00:00:00"
            ),0)+ ifnull((
            SELECT sum(csd.cantidad_origen) FROM conversiones_sucursales_descripcion AS csd
            LEFT JOIN conversiones_sucursales AS cs ON( csd.id_conversion = cs.id_conversion )
            WHERE cs.fecha > "2011-06-03 00:00:00" 
            AND csd.id_producto_origen = almacenes_existencias.id_producto
            AND csd.id_almacen_origen = almacenes_existencias.id_almacen
            ), 0)+ ifnull((
            SELECT sum(aet.cantidad) FROM almacenes_salidas_ordenes_transferencias_descripcion AS aet
            LEFT JOIN almacenes_salidas_ordenes_transferencias AS t ON( aet.id_salida_orden_transferencia = t.id_salida_orden_transferencia)
            WHERE aet.id_producto = almacenes_existencias.id_producto
            AND aet.almacen_origen = almacenes_existencias.id_almacen
            AND t.fecha > "2011-06-03 00:00:00"
            ),0)+ ifnull((
            SELECT sum(asud.cantidad) FROM almacenes_salidas_uso_interno_descripcion AS asud
            LEFT JOIN almacenes_salidas_uso_interno AS asui ON( asud.id_salida_uso_interno = asui.id_salida_uso_interno)
            WHERE almacenes_existencias.id_producto = asud.id_producto
            AND almacenes_existencias.id_almacen = asud.id_almacen #and aj.fecha < "2011-07-15 23:59:59"
            AND asui.fecha_aplicacion > "2011-06-03 00:00:00"
            ),0))+ IF(almacenes_existencias.id_almacen = 70,(
            SELECT SUM(gs.cantidad) FROM vehiculos_recargas_combustibles AS gs
            WHERE gs.id_combustible = p.id_producto
            ),0)) - ((ifnull((
            SELECT sum(aed.cantidad) FROM almacenes_entradas_ordenes_descripcion AS aed
            LEFT JOIN almacenes_entradas_ordenes AS aeo ON( aed.id_entrada_orden = aeo.id_entrada_orden)
            WHERE aed.id_almacen = almacenes_existencias.id_almacen
            AND aed.id_producto = almacenes_existencias.id_producto
            AND aeo.fecha_entrada > "2011-06-03 00:00:00"
            ),0)+ ifnull((
            SELECT sum(aet.cantidad) FROM almacenes_entradas_transferencias_descripcion AS aet
            LEFT JOIN almacenes_entradas_transferencias AS t ON( aet.id_entrada_transferencia = t.id_entrada_transferencia)
            WHERE aet.id_producto = almacenes_existencias.id_producto
            AND aet.almacen_destino = almacenes_existencias.id_almacen
            AND t.fecha > "2011-06-03 00:00:00"
            ),0)+ ifnull((
            SELECT sum(aet.cantidad) FROM almacenes_entradas_devoluciones_descripcion AS aet 
            WHERE aet.id_producto = almacenes_existencias.id_producto
            AND aet.id_almacen = almacenes_existencias.id_almacen
            AND aet.aplicacion = "entrada"
            AND aet.fecha > "2011-06-03 00:00:00"
            ),0)+ ifnull((
            SELECT sum(csd.cantidad_destino) FROM conversiones_sucursales_descripcion AS csd
            LEFT JOIN conversiones_sucursales AS cs ON( csd.id_conversion = cs.id_conversion)
            WHERE csd.id_producto_destino = almacenes_existencias.id_producto
            AND csd.id_almacen_destino = almacenes_existencias.id_almacen
            AND cs.fecha > "2011-06-03 00:00:00"
            ),0)+ ifnull((
            SELECT sum(ajd.cantidad) FROM almacenes_ajustes_descripcion AS ajd
            LEFT JOIN almacenes_ajustes AS aj ON(ajd.id_ajuste = aj.id_ajuste)
            WHERE ajd.id_producto = almacenes_existencias.id_producto
            AND ajd.id_almacen = almacenes_existencias.id_almacen
            AND aj.fecha > "2011-06-03 00:00:00"
            ),0)))),2) AS existencia_fisica'),
            \DB::raw('IFNULL((
            SELECT IFNULL(e_a.existencia, 0) FROM almacenes_existencias_prov_copy AS e_a
            WHERE almacenes_existencias.id_almacen = e_a.id_almacen
            AND almacenes_existencias.id_producto = e_a.id_producto
            ), 0) AS existencia_3_junio'),
            \DB::raw('(SELECT existencia_3_junio) - (SELECT existencia_fisica) AS diferencia')
        )
            ->leftJoin('almacenes AS a', 'almacenes_existencias.id_almacen', '=', 'a.id_almacen')
            ->leftJoin('productos AS p', 'almacenes_existencias.id_producto', '=', 'p.id_producto');

        if (!empty($datos['sucursal_id'])) {
            $query->whereIn('almacenes_existencias.id_sucursal', $datos['sucursal_id']);
        }

        return $query->having('diferencia', '!=', 0)
            ->orderBy('almacenes_existencias.id_existencia', 'ASC')
            ->get();
    }

    public function costeo($datos)
    {

        $query = $this->from("almacenes_existencias as e");
        $query->leftJoin("productos as p", "p.id_producto", "=", "e.id_producto");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "e.id_sucursal");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "e.id_almacen");

        $query->select(
            \DB::raw("sum((e.existencia + IFNULL((SELECT sum(r.reservado) FROM almacenes_reservados AS r WHERE r.id_producto = e.id_producto AND r.id_almacen = e.id_almacen),0) + IFNULL((SELECT sum(t.cantidad) FROM almacenes_reservados_transferencias AS t WHERE t.id_producto = e.id_producto AND t.almacen_origen = e.id_almacen),0)) * e.precio_costo) as costo"),
            "s.nombre as sucursal",
            "s.id_sucursal"
        );

        if (!empty($datos["id_sucursal"])) {

            $query->whereIn("e.id_sucursal", $datos["id_sucursal"]);

        }

        $query->where("s.inventario", "=", 0);
        $query->where("p.activo", "=", 0);

        $query->groupBy("s.id_sucursal");

        $query->orderBy("costo", "desc");

        //dd($query->toSql());
        return $query->get();

    }

    public function costeoFamilias($datos)
    {

        $query = $this->from("almacenes_existencias as e");
        $query->leftJoin("productos as p", "p.id_producto", "=", "e.id_producto");
        $query->leftJoin("productos_familias as f", "f.id_familia", "=", "p.id_familia");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "e.id_sucursal");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "e.id_almacen");

        $query->select(
            \DB::raw("sum((e.existencia + IFNULL((SELECT sum(r.reservado) FROM almacenes_reservados AS r WHERE r.id_producto = e.id_producto AND r.id_almacen = e.id_almacen),0) + IFNULL((SELECT sum(t.cantidad) FROM almacenes_reservados_transferencias AS t WHERE t.id_producto = e.id_producto AND t.almacen_origen = e.id_almacen),0)) * e.precio_costo) as costo"),
            "s.nombre as sucursal",
            "s.id_sucursal",
            "f.familia",
            "f.id_familia"
        );

        if (!empty($datos["id_sucursal"])) {

            $query->where("e.id_sucursal", $datos["id_sucursal"]);

        }

        $query->where("s.inventario", "=", 0);
        $query->where("p.activo", "=", 0);

        $query->groupBy("f.familia");

        $query->orderBy("costo", "desc");

        //dd($query->toSql());
        return $query->get();

    }

    public function costeoProductos($datos)
    {

        $query = $this->from("almacenes_existencias as e");
        $query->leftJoin("productos as p", "p.id_producto", "=", "e.id_producto");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "e.id_sucursal");

        $query->select(
            "p.codigo_producto",
            "p.descripcion",
            \DB::raw("IFNULL(SUM((e.existencia + IFNULL((SELECT sum(r.reservado) FROM almacenes_reservados AS r WHERE r.id_producto = e.id_producto AND r.id_almacen = e.id_almacen),0) + IFNULL((SELECT sum(t.cantidad) FROM almacenes_reservados_transferencias AS t WHERE t.id_producto = e.id_producto AND t.almacen_origen = e.id_almacen),0))),0) AS total_existencia"),
            \DB::raw("ifnull(e.precio_costo,0) as precio_costo"),
            \DB::raw("sum((e.existencia + IFNULL((SELECT sum(r.reservado) FROM almacenes_reservados AS r WHERE r.id_producto = e.id_producto AND r.id_almacen = e.id_almacen),0) + IFNULL((SELECT sum(t.cantidad) FROM almacenes_reservados_transferencias AS t WHERE t.id_producto = e.id_producto AND t.almacen_origen = e.id_almacen),0)) * e.precio_costo) as total")
        );

        $query->where("s.inventario", "=", 0);
        $query->where("p.activo", "=", 0);

        if (!empty($datos["id_sucursal"])) {

            $query->where("e.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["id_familia"])) {

            $query->where("p.id_familia", $datos["id_familia"]);

        }

        $query->groupBy("p.id_producto");

        $query->orderBy("total", "desc");

        //dd($query->toSql());
        return $query->get();

    }

    /*
     * FUNCIÓN PARA COSTEO DE INVENTARIO GENERAL O POR SUCURSAL
     * SE SOLICITA ÚNICAMENTE EL ID DE LA SUCURSAL DE MANERA OPCIONAL
     * SE DEVUELVE EXISTENCIA TOTAL, COSTO DEL INVENTARIO, (AMBOS AGRUPADOS POR ROTACIÓN) Y EL COSTO TOTAL DEL INVENTARIO
     * PARA EL CÁLCULO DEL PORCENTAJE EQUIVALENTE.
     * */

    public function costeoInventario($datos)
    {
        $select = [
            'cs.nombre AS sucursal',
            'cs.id_sucursal',
            \DB::raw('IFNULL((SUM((IFNULL(ae.existencia, 0) + IFNULL((SELECT SUM(ar.reservado) FROM almacenes_reservados as ar where ar.id_producto = ae.id_producto and ar.id_almacen = ae.id_almacen), 0) + IFNULL((SELECT SUM(art.cantidad) FROM almacenes_reservados_transferencias as art where art.id_producto = ae.id_producto and art.almacen_origen = ae.id_almacen),0)) * ae.precio_costo)),0) AS costo')
        ];
        $query = $this->from('almacenes_existencias AS ae')
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'ae.id_producto')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'ae.id_sucursal');

        $query = $query
            ->where('cs.inventario', 0)
            ->orderBy('costo', 'DESC');

        if ($datos['tipo'] != "Todos") {
            $query = $query->where('p.activo', $datos['tipo']);
        }

        if (!empty($datos['sucursal'])) {
            if (!empty($datos['sucursal_id'])) {
                $query = $query->where('ae.id_sucursal', $datos['sucursal_id']);
            } else {
                $query = $query->groupBy('ae.id_sucursal');
            }
        }

        if (!empty($datos['familia'])) {
            $select[] = "pf.familia";
            $select[] = "pf.id_familia";
            $query = $query->leftJoin('productos_familias AS pf', function ($join) {
                $join->on('pf.id_familia', '=', 'ae.id_familia')
                    ->whereNotNull('pf.id_familia');
            });
            if (!empty($datos['familia_id'])) {
                $query = $query->where('ae.id_familia', $datos['familia_id']);
            } else {
                $query = $query->groupBy('ae.id_familia');
            }
        }

        if (!empty($datos['linea'])) {
            $select[] = "pl.linea";
            $select[] = "pl.id_linea";
            $query = $query->leftJoin('productos_lineas AS pl', 'pl.id_linea', '=', 'ae.id_linea');
            if (!empty($datos['linea_id'])) {
                $query = $query->where('ae.id_linea', $datos['linea_id']);
            } else {
                $query = $query->groupBy('ae.id_linea');
            }
        }

        if (!empty($datos['producto'])) {
            $select[] = "p.codigo_producto";
            $select[] = "p.descripcion";
            $query = $query->groupBy('ae.id_producto');
        }

        $query->select($select);

        return $query->get();
    }

    /*
     * FUNCIÓN PARA COSTEO DE INVENTARIO GENERAL O POR SUCURSAL DETALLADO POR PRODUCTOS
     * SE SOLICITA ÚNICAMENTE EL ID DE LA SUCURSAL DE MANERA OPCIONAL
     * SE DEVUELVE EL CÓDIGO DEL PRODUCTO, DESCRIPCIÓN, EXISTENCIA, COSTO DEL INVENTARIO Y EL COSTO TOTAL DE INVENTARIO
     * PARA EL CÁLCULO DEL PORCENTAJE EQUIVALENTE.
     * */

    public function costeoInventarioProductos($parametros)
    {
        $query = $this->from('almacenes_existencias AS ae')
            ->select(
                'p.codigo_producto',
                'p.descripcion',
                \DB::raw('ae.existencia + IFNULL((SELECT sum(r.reservado) FROM almacenes_reservados AS r WHERE r.id_producto = ae.id_producto AND r.id_almacen = ae.id_almacen), 0) + IFNULL((SELECT sum(t.cantidad) FROM almacenes_reservados_transferencias AS t WHERE t.id_producto = ae.id_producto AND t.almacen_origen = ae.id_almacen), 0) AS existencia'),
                \DB::raw('((ae.existencia + IFNULL((SELECT sum(r.reservado) FROM almacenes_reservados AS r WHERE r.id_producto = ae.id_producto AND r.id_almacen = ae.id_almacen), 0) + IFNULL((SELECT sum(t.cantidad) FROM almacenes_reservados_transferencias AS t WHERE t.id_producto = ae.id_producto AND t.almacen_origen = ae.id_almacen), 0)) * ae.precio_costo) AS costo_inventario'),
                \DB::raw('(SELECT SUM((ae3.existencia + IFNULL((SELECT sum(r.reservado) FROM almacenes_reservados AS r WHERE r.id_producto = ae3.id_producto AND r.id_almacen = ae3.id_almacen), 0) + IFNULL((SELECT sum(t.cantidad) FROM almacenes_reservados_transferencias AS t WHERE t.id_producto = ae3.id_producto AND t.almacen_origen = ae3.id_almacen), 0)) * ae3.precio_costo) FROM almacenes_existencias AS ae3 LEFT JOIN productos AS p3 ON p3.id_producto = ae3.id_producto LEFT JOIN cat_sucursales AS cs ON cs.id_sucursal = ae3.id_sucursal WHERE p3.estatus_producto = "activo" AND p3.activo = 0 AND ae3.rotacion = "' . $parametros['rotacion'] . '" ' . (isset($parametros['sucursal_id']) ? 'AND ae3.id_sucursal = ' . $parametros['sucursal_id'] : 'AND cs.inventario = 0') . ') AS costo_inventario_total')
            );

        if (isset($parametros['sucursal_id'])) {
            $query = $query->where('ae.id_sucursal', $parametros['sucursal_id']);
        } else {
            $query = $query->addSelect('cs.nombre AS sucursal')
                ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'ae.id_sucursal');
        }

        $query = $query->leftJoin('productos AS p', 'p.id_producto', '=', 'ae.id_producto')
            ->where('p.estatus_producto', 'activo')
            ->where('p.activo', 0)
            ->where('rotacion', $parametros['rotacion'])
            ->orderBy('costo_inventario', 'DESC');

        return $query->get();
    }

    public function buscarProductos($parametros)
    {
        $query = $this->from('almacenes_existencias AS ae')
            ->select(
                'ae.id_sucursal',
                'p.id_producto'
            )
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'ae.id_producto')
            ->where('p.estatus_producto', 'activo')
            ->where('p.activo', 0)
            ->where('ae.id_sucursal', $parametros['sucursal_id']);

        return $query->get();

    }

    public function costeoInventarioTotal()
    {
        $query = $this->from('almacenes_existencias AS ae')
            ->select(
                \DB::raw('SUM((existencia + IFNULL((SELECT sum(r.reservado) FROM almacenes_reservados AS r WHERE r.id_producto = ae.id_producto AND r.id_almacen = ae.id_almacen), 0) + IFNULL((SELECT sum(t.cantidad) FROM almacenes_reservados_transferencias AS t WHERE t.id_producto = ae.id_producto AND t.almacen_origen = ae.id_almacen), 0)) * precio_costo) AS total')
            )
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'ae.id_producto')
            ->where('p.estatus_producto', 'activo')
            ->where('p.activo', 0);

        return $query->first();
    }

    public function buscarProductosAnalisis($parametros)
    {
        $query = $this->from('almacenes_existencias AS ae')
            ->select(
                'ae.existencia',
                'p.codigo_producto',
                'p.descripcion',
                'p.id_producto',
                'pl.linea',
                \DB::raw('p_costo(ae.id_producto) AS costo'),
                \DB::raw('(SELECT (SUM(vd.cantidad * vd.costo) / 3) FROM  ventas_descripcion AS vd WHERE DATE(fecha) BETWEEN "' . $parametros['fecha_inicial'] . '" AND "' . $parametros['fecha_final'] . '" AND vd.id_producto = ae.id_producto AND vd.id_sucursal = ae.id_sucursal) AS pmv'),
                \DB::raw('(SELECT pmv) / 30 AS pdv'),
                'ae.rotacion'
            )
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'ae.id_producto')
            ->leftJoin('productos_lineas AS pl', 'pl.id_linea', '=', 'p.id_linea')
            ->where('ae.id_sucursal', $parametros['sucursal_id'])
            ->where('ae.rotacion', $parametros['rotacion']);

        if (isset($parametros['familia_id'])) {
            $query = $query->whereIn('p.id_familia', $parametros['familia_id']);
        }

        if (isset($parametros['linea_id'])) {
            $query = $query->whereIn('p.id_linea', $parametros['linea_id']);
        }

        return $query->get();
    }

    public function buscarRotacionNula($datos)
    {

        $query = $this->from("almacenes_existencias as ae");
        $query->leftJoin("cfd_datos_emisor as s", "s.id_sucursal", "=", "ae.id_sucursal");
        $query->leftJoin("cat_sucursales as suc", "suc.id_sucursal", "=", "s.id_sucursal");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "ae.id_almacen");
        $query->leftJoin("productos_familias as f", "f.id_familia", "=", "ae.id_familia");
        $query->leftJoin("productos_lineas as l", "l.id_linea", "=", "ae.id_linea");
        $query->leftJoin("productos as p", "p.id_producto", "=", "ae.id_producto");

        $query->select(
            "ae.id_existencia",
            "ae.id_producto",
            "ae.id_almacen",
            "ae.id_sucursal",
            "suc.nombre as sucursal",
            "suc.inventario",
            "a.almacen",
            "p.codigo_producto",
            "p.descripcion",
            "f.familia",
            "l.linea",
            \DB::raw("ifnull((ae.existencia + obtenerReservado(ae.id_producto, ae.id_almacen) + obtenerReservadoTransferencias(ae.id_producto, ae.id_almacen)),0) as existencia"),
            \DB::raw("ifnull(p_costo(ae.id_producto),0) as costo"),
            "ae.rotacion",
            \DB::raw("ifnull((existencia * (SELECT costo)),0) as valor_inventario")
        );

        if (!empty($datos["rotacion"])) {

            $query->where("ae.rotacion", $datos["rotacion"]);

        } else {

            $query->whereIn("ae.rotacion", ["Nula", "Nula con estacionalidad"]);

        }

        if (!empty($datos["id_sucursal"])) {

            $query->whereIn("ae.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["id_familia"])) {

            $query->whereIn("ae.id_familia", $datos["id_familia"]);

        }

        if (!empty($datos["id_linea"])) {

            $query->whereIn("ae.id_linea", $datos["id_linea"]);

        }

        if (!empty($datos["hijos"])) {

            $query->whereNotIn("ae.id_producto", $datos["hijos"]);

        }

        if (!empty($datos["cero"])) {

            if ($datos["cero"] == "no") {

                $query->having("existencia", ">", 0);

            }

        }

        $query->where('p.activo', 0)
            ->where('p.clasificacion', 'venta');

        $query->whereNotNull("s.id_sucursal");

        return $query->get();

    }

    public function almacenesProductos($datos)
    {

        $query = $this->from("almacenes_existencias as ae");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "ae.id_almacen");
        $query->leftJoin("cfd_datos_emisor as s", "s.id_sucursal", "=", "ae.id_sucursal");

        $query->select(
            "ae.*",
            "a.almacen",
            "s.nombre as sucursal",
            \DB::raw("ifnull(ae.minimo_compra,'') as minimo_compra"),
            \DB::raw("ifnull(ae.maximo_compra,'') as maximo_compra")
        );

        $query->whereNotNull("s.id_sucursal");

        if (!empty($datos["id_producto"])) {

            $query->where("ae.id_producto", $datos["id_producto"]);

        }

        if (!empty($datos["id_existencia"])) {

            $query->where("ae.id_existencia", $datos["id_existencia"]);

        }

        if (!empty($datos["first"])) {

            return $query->first();

        }

        return $query->get();

    }


    public function buscarExistencia($datos)
    {

        $query = $this->from("almacenes_existencias as a");
        $query->select(\DB::raw("obtenerExistenciaSucursal(a.id_producto, a.id_sucursal) as existencia"))
            ->where("a.id_producto", $datos["id_producto"])
            ->where("a.id_sucursal", $datos["id_sucursal"]);

        return $query->first();
    }

    public function consultaInventario($params)
    {
        $query = $this->from('almacenes_existencias AS ae')
            ->select(
                'ae.existencia',
                'a.almacen',
                'cs.nombre AS sucursal'
            )
            ->leftJoin('cfd_datos_emisor AS cde', 'cde.id_sucursal', '=', 'ae.id_sucursal')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'cde.id_sucursal')
            ->leftJoin('almacenes AS a', 'a.id_almacen', '=', 'ae.id_almacen')
            ->where('ae.id_producto', $params['producto_id'])
            ->where('cs.inventario', 0)
            ->where('ae.existencia', '>', 0);

        return $query->get();

    }
}