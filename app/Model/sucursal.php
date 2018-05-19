<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class sucursal extends Model
{
    //
    protected $table = "cat_sucursales";
    protected $primaryKey = "id_sucursal";
    protected $fillable = [
        'nombre'
    ];

    public function buscarPorProdecimiento($datos)
    {
        $query = $this->leftJoin("calidad_procedimiento_sucursal as ps", function ($join) use ($datos) {
            $join->on("cat_sucursales.id_sucursal", "=", "ps.id_sucursal")
                ->where("ps.procedimiento_id", "=", $datos["procedimiento_id"]);
        });


        return $query->get();

    }

    public function buscar($datos)
    {
        $query = $this->leftJoin("cat_estados as e", "e.id_estado", "=", "cat_sucursales.id_estado");
        $query->leftJoin("cat_sucursales_logistica as csl", "csl.id_sucursal_destino", "=", "cat_sucursales.id_sucursal");
        //$query->leftJoin("categorias_sucursales as cs", "cs.id", "=", "cat_sucursales.categoria_sucursal_id");

        $select = [
            "cat_sucursales.*",
            \DB::raw("ifnull(cat_sucursales.direccion,'') as direccion"),
            \DB::raw("ifnull(cat_sucursales.colonia,'') as colonia"),
            \DB::raw("ifnull(cat_sucursales.ciudad,'') as ciudad"),
            \DB::raw("ifnull(cat_sucursales.logo_ticket,'') as logo_ticket"),
            \DB::raw("ifnull(cat_sucursales.mapa,'') as mapa"),
            \DB::raw("ifnull(cat_sucursales.telefono,'000 0000000') as telefono"),
            \DB::raw("ifnull(cat_sucursales.email,'') as email"),
            \DB::raw("ifnull(e.estado,'') as estado"),
            \DB::raw("ifnull(csl.id_sucursal_destino,'') as id_sucursal_destino"),
            \DB::raw("ifnull(csl.estatus_logistica_sucursales,'') as estatus_logistica_sucursales"),
            \DB::raw("ifnull(cat_sucursales.monto_remision,0) as monto_remision")
            //\DB::raw("ifnull(cs.nombre,'') as categoria")
        ];

        $query->select(

            $select

        );

        if (!empty($datos["sucursal"])) {
            $query = $query->where("nombre", "like", "%" . $datos["sucursal"] . "%");
            if (!empty($datos["first"])) {
                return $query->first();
            }

        }
        if (!empty($datos["activo"])) {
            $query = $query->where("activo", "=", $datos["activo"]);
        }

        if (isset($datos["inventario"])) {
            $query = $query->where("inventario", "=", $datos["inventario"]);
        }

        if (!empty($datos["id_sucursal"])) {
            if (count($datos["id_sucursal"]) > 1) {

                $query = $query->whereIn("id_sucursal", $datos["id_sucursal"]);

            } else {

                $query = $query->where("id_sucursal", "=", $datos["id_sucursal"]);
                return $query->first();

            }
        }

        $query->groupBy("cat_sucursales.id_sucursal");

        return $query->get();

    }

    public function sucursalesDisponibles($datos)
    {

        if (empty($datos["region_id"])) {

            $query = $this->where(\DB::raw("(select count(*) from calidad_region_sucursal as cs" .
                " LEFT JOIN calidad_region as r on r.id = cs.region_id" .
                " where r.estatus = 'activo'" .
                " and cs.sucursal_id=cat_sucursales.id_sucursal)"), "=", 0);
        } else {


            $query = $this->where(\DB::raw("(select count(*) from calidad_region_sucursal as cs" .
                " LEFT JOIN calidad_region as r on r.id = cs.region_id" .
                " where r.estatus = 'activo'" .
                " and r.id != " . $datos["region_id"] .
                " and cs.sucursal_id=cat_sucursales.id_sucursal)"), "=", 0);
            $query->leftJoin("calidad_region_sucursal as rs", function ($join) use ($datos) {
                $join->on("rs.sucursal_id", "=", "cat_sucursales.id_sucursal")
                    ->where("rs.region_id", "=", $datos["region_id"]);

            });

            if (!empty($datos["asignadas"])) {
                $query->whereNotNull("rs.id");

            }

            $query->select(
                "cat_sucursales.*", 'rs.id as region_sucursal_id', 'rs.region_id',
                \DB::raw("if(rs.id is null,'NO','SI') as asignado")
            );

        }

        return $query->get();
    }

    public function sucursalesActivas()
    {
        return $this::where("activo", "=", "si")->select(
            "cat_sucursales.*",
            \DB::raw("'NO' as asignado")
        )->get()->toArray();
    }


    /**
     * @param array $datos
     * @return mixed
     */
    public function ventasCompras(array $datos)
    {

        /*
         * CONSULTA PARA OBTENER VENTAS TOTALES, DEVOLUCIONES, COMPRAS ACUMULADAS HASTA EL PERIODO FINAL DEFINIDO
         * Y ORDENES DE COMPRA CON ACTIVIDAD DENTRO DEL PERIODO DEFINIDO
         */

        /*
         * SE DEFINE LA TABLA PRINCIPAL QUE REGIRÁ LA CONSULTA Y SE LE AÑADE UN ALIAS
         * */
        $query = $this->from('cat_sucursales AS ct');

        // SUBCONSULTA PARA OBTENER LAS VENTAS NETAS POR SUCURSAL
        $ventas = '(SELECT IFNULL(SUM(vd.cantidad * vd.precio), 0) AS total_ventas FROM ventas_descripcion AS vd LEFT JOIN ventas AS v ON v.id_venta = vd.id_venta LEFT JOIN productos AS p ON p.id_producto = vd.id_producto WHERE (v.id_sucursal = ct.id_sucursal) AND v.fecha BETWEEN "' . $datos["fecha_inicial"] . '" AND "' . $datos["fecha_final"] . '") AS ventas';
        // SUBCONSULTA PARA OBTENER LAS DEVOLUCIONES POR SUCURSAL
        $devoluciones = '(SELECT IFNULL(SUM(dd.sin_iva * dd.cantidad), 0) AS total_devoluciones FROM devoluciones_descripcion AS dd LEFT JOIN devoluciones AS d ON d.id_devolucion = dd.id_devolucion LEFT JOIN productos AS p ON p.id_producto = dd.id_producto WHERE d.id_sucursal = ct.id_sucursal AND d.fecha BETWEEN "' . $datos["fecha_inicial"] . '" AND "' . $datos["fecha_final"] . '") AS devoluciones';

        /*
         * COMPRAS ACUMULADAS (SOLO SE TOMA EN CUENTA EL PERIODO FINAL DEFINIDO)
         * */
        // SUBCONSULTA PARA OBTENER LAS ÓRDENES DE COMPRA QUE SE ENCUENTRAN AUTORIZADAS Y SE CREARON
        $comprasPendientes = '(IFNULL((SELECT SUM(co.subtotal_compra) FROM compras_ordenes AS co WHERE DATE(co.fecha_orden) <= "' . $datos['fecha_final'] . '" AND co.estatus = "autorizada" AND co.id_sucursal = ct.id_sucursal AND co.concepto_emision = "compra"), 0)) AS compras_pendientes';
        // SUBCONSULTA PARA OBTENER LAS ORDENES DE COMPRA QUE FUERON CREADAS E INGRESADAS
        $comprasIngresadas = '(IFNULL((SELECT SUM(aeod.cantidad * cod.precio) FROM compras_ordenes_descripcion AS cod LEFT JOIN compras_ordenes AS co ON co.id_orden = cod.id_orden LEFT JOIN almacenes_entradas_ordenes_descripcion AS aeod ON aeod.id_orden_descripcion = cod.id_orden_descripcion LEFT JOIN compras_ordenes_facturas AS cof ON cof.id_orden = cod.id_orden WHERE DATE(co.fecha_orden) <= "' . $datos["fecha_final"] . '" AND DATE(cof.fecha_pago) <= "' . $datos["fecha_final"] . '" AND co.estatus IN("autorizada" , "backorder") AND cof.tipo_pago != "anticipado" AND cof.estatus = "pagada" AND co.concepto_emision = "compra" AND co.id_sucursal = ct.id_sucursal), 0)) AS compras_ingresadas';
        // SUBCONSULTA PARA OBTENER LAS ÓRDENES DE COMPRA QUE YA TIENEN RELACIONADAS UNA O VARIAS FACTURAS PAGADAS
        $comprasPagadasCc = '(IFNULL((SELECT SUM(cof.subtotal) FROM compras_ordenes AS co LEFT JOIN compras_ordenes_facturas AS cof ON cof.id_orden = co.id_orden WHERE DATE(co.fecha_orden) <= "' . $datos['fecha_final'] . '" AND DATE(cof.fecha_pago) <= "' . $datos['fecha_final'] . '" AND co.estatus IN("autorizada" , "backorder") AND cof.tipo_pago != "anticipado" AND cof.estatus = "pagada" AND co.concepto_emision = "compra" AND co.id_sucursal = ct.id_sucursal), 0)) AS compras_pagadas_cc';
        // SUBCONSULTA PARA OBTENER LAS ÓRDENES DE COMPRA QUE YA CUENTAN CON PAGO TOTAL DEL MONTO DE LA MISMA PERO QUE AÚN NO SE HAN INGRESADO AL INVENTARIO
        $comprasPagadasAnticipo = '(IFNULL((SELECT SUM(cof.subtotal) FROM compras_ordenes AS co LEFT JOIN compras_ordenes_facturas AS cof ON cof.id_orden = co.id_orden WHERE DATE(co.fecha_orden) <= "' . $datos['fecha_final'] . '" AND DATE(cof.fecha_pago) <= "' . $datos['fecha_final'] . '" AND cof.tipo_pago = "anticipado" AND cof.estatus = "pagada" AND co.concepto_emision = "compra" AND co.id_sucursal = ct.id_sucursal), 0)) AS compras_pagadas_anticipo';
        // SUBCONSULTA PARA OBTENER TODAS LAS ÓRDENES DE COMPRA GENERADAS POR GASTO
        $comprasGastos = '(IFNULL((SELECT SUM(co.subtotal_compra) FROM compras_ordenes AS co WHERE DATE(co.fecha_orden) <= "' . $datos['fecha_final'] . '" AND co.estatus = "finalizada" AND co.concepto_emision = "gasto" AND co.id_sucursal = ct.id_sucursal), 0)) AS compras_gastos';

        /*
         * ÓRDENES DE COMPRA (SE TOMA EN CUENTA PERIODO INICIAL Y FINAL DEFINIDO)
         * */
        // SUBCONSULTA PARA OBTENER LAS ÓRDENES DE COMPRA QUE SE ENCUENTRAN AUTORIZADAS Y SE CREARON
        $ordenesCompraPendientes = '(IFNULL((SELECT SUM(co.subtotal_compra) FROM compras_ordenes AS co WHERE DATE(co.fecha_orden) BETWEEN "' . $datos['fecha_inicial'] . '" AND "' . $datos['fecha_final'] . '" AND co.estatus = "autorizada" AND co.id_sucursal = ct.id_sucursal AND co.concepto_emision = "compra"), 0)) AS ordenes_compra_pendientes';
        // SUBCONSULTA PARA OBTENER LAS ORDENES DE COMPRA QUE FUERON CREADAS E INGRESADAS
        $ordenesCompraIngresadas = '(IFNULL((SELECT SUM(aeod.cantidad * cod.precio) FROM compras_ordenes_descripcion AS cod LEFT JOIN compras_ordenes AS co ON co.id_orden = cod.id_orden LEFT JOIN almacenes_entradas_ordenes_descripcion AS aeod ON aeod.id_orden_descripcion = cod.id_orden_descripcion LEFT JOIN almacenes_entradas_ordenes AS aeo ON aeo.id_entrada_orden = aeod.id_entrada_orden LEFT JOIN compras_ordenes_facturas AS cof ON cof.id_orden = cod.id_orden WHERE DATE(co.fecha_orden) BETWEEN "' . $datos["fecha_inicial"] . '" AND "' . $datos["fecha_final"] . '" AND DATE(aeo.fecha_entrada) BETWEEN "' . $datos["fecha_inicial"] . '" AND "' . $datos["fecha_final"] . '" AND co.estatus IN ("autorizacion", "backorder") AND cof.estatus = "pagada" AND co.concepto_emision = "compra" AND co.id_sucursal = ct.id_sucursal), 0)) AS ordenes_compra_ingresadas';
        // SUBCONSULTA PARA OBTENER LAS ÓRDENES DE COMPRA QUE YA TIENEN RELACIONADAS UNA O VARIAS FACTURAS PAGADAS
        $ordenesCompraPagadasCc = '(IFNULL((SELECT SUM(cof.subtotal) FROM compras_ordenes AS co LEFT JOIN compras_ordenes_facturas AS cof ON cof.id_orden = co.id_orden WHERE DATE(co.fecha_orden) BETWEEN "' . $datos['fecha_inicial'] . '" AND "' . $datos['fecha_final'] . '" AND DATE(cof.fecha_pago) BETWEEN "' . $datos['fecha_inicial'] . '" AND "' . $datos['fecha_final'] . '" AND co.estatus IN("autorizada" , "backorder") AND cof.tipo_pago != "anticipado" AND cof.estatus = "pagada" AND co.concepto_emision = "compra" AND co.id_sucursal = ct.id_sucursal), 0)) AS ordenes_compra_pagadas_cc';
        // SUBCONSULTA PARA OBTENER LAS ÓRDENES DE COMPRA QUE YA CUENTAN CON PAGO TOTAL DEL MONTO DE LA MISMA PERO QUE AÚN NO SE HAN INGRESADO AL INVENTARIO
        $ordenesCompraPagadasAnticipo = '(IFNULL((SELECT SUM(cof.subtotal) FROM compras_ordenes AS co LEFT JOIN compras_ordenes_facturas AS cof ON cof.id_orden = co.id_orden WHERE DATE(co.fecha_orden) BETWEEN "' . $datos['fecha_inicial'] . '" AND "' . $datos['fecha_final'] . '" AND DATE(cof.fecha_pago) BETWEEN "' . $datos['fecha_inicial'] . '" AND "' . $datos['fecha_final'] . '" AND cof.tipo_pago = "anticipado" AND cof.estatus = "pagada" AND co.concepto_emision = "compra" AND co.id_sucursal = ct.id_sucursal), 0)) AS ordenes_compra_pagadas_anticipo';
        // SUBCONSULTA PARA OBTENER TODAS LAS ÓRDENES DE COMPRA GENERADAS POR GASTO
        $ordenesCompraGastos = '(IFNULL((SELECT SUM(co.subtotal_compra) FROM compras_ordenes AS co WHERE DATE(co.fecha_orden) BETWEEN "' . $datos['fecha_inicial'] . '" AND "' . $datos['fecha_final'] . '" AND co.estatus = "finalizada" AND co.concepto_emision = "gasto" AND co.id_sucursal = ct.id_sucursal), 0)) AS ordenes_compra_gastos';

        /*
         * SE COLOCAN LAS SUBCONSULTAS DEFINIDAS ANTERIORMENTE DENTRO DE UN ARREGLO PARA POSTERIORMENTE LLAMARLAS
         * HACIA UNA CONSULTA Y PODER VISUALIZAR ASÍ LA INFORMACIÓN
        */
        $select = [
            'ct.nombre',
            'ct.id_sucursal',
            \DB::raw($ventas),
            \DB::raw($devoluciones),
            /*
             * COMPRAS ACUMULADAS
             * */
            \DB::raw($comprasPendientes),
            \DB::raw($comprasIngresadas),
            \DB::raw($comprasPagadasCc),
            \DB::raw($comprasPagadasAnticipo),
            \DB::raw($comprasGastos),
            /*
             * ÓRDENES DE COMPRA
             * */
            \DB::raw($ordenesCompraPendientes),
            \DB::raw($ordenesCompraIngresadas),
            \DB::raw($ordenesCompraPagadasCc),
            \DB::raw($ordenesCompraPagadasAnticipo),
            \DB::raw($ordenesCompraGastos)
        ];

        /*
         * SE INVOCA A LA VARIABLE QUE CONTIENE EL ARREGLO DE SUBCONSULTAS, SE AÑADEN CONDICIONES DE SUCURSALES ACTIVAS Y QUE UTILICEN INVENTARIO
         * Y POR ÚLTIMO SE DEVUELVE EL RESULTADO PARA SER PROCESADO EN EL CONTROLADOR CORRESPONDIENTE.
         */
        $query = $query->select($select)
            ->where('ct.activo', 'si')
            ->where('ct.inventario', 0);
        return $query->get();
    }

    /*public function ventasComprasFamilias($datos)
    {
        $ventas = '(SELECT IFNULL(SUM(vd.cantidad * vd.precio), 0) AS total_ventas FROM ventas_descripcion AS vd LEFT JOIN ventas AS v ON v.id_venta = vd.id_venta LEFT JOIN productos AS p ON p.id_producto = vd.id_producto WHERE (v.id_sucursal = ct.id_sucursal) AND v.fecha BETWEEN "' . $datos["fecha_inicial"] . '" AND "' . $datos["fecha_final"] . '") AS ventas';
        $devoluciones = '(SELECT IFNULL(SUM(dd.sin_iva * dd.cantidad), 0) AS total_devoluciones FROM devoluciones_descripcion AS dd LEFT JOIN devoluciones AS d ON d.id_devolucion = dd.id_devolucion LEFT JOIN productos AS p ON p.id_producto = dd.id_producto WHERE d.id_sucursal = ct.id_sucursal AND d.fecha BETWEEN "' . $datos["fecha_inicial"] . '" AND "' . $datos["fecha_final"] . '") AS devoluciones';
        $compras = '(SELECT IFNULL(SUM(aeod.cantidad * cod.precio), 0) as total_compras FROM almacenes_entradas_ordenes_descripcion AS aeod LEFT JOIN compras_ordenes_descripcion AS cod ON cod.id_orden_descripcion = aeod.id_orden_descripcion LEFT JOIN almacenes_entradas_ordenes AS aeo ON aeod.id_entrada_orden = aeo.id_entrada_orden LEFT JOIN productos AS p ON p.id_producto = aeod.id_producto WHERE aeod.id_sucursal = ct.id_sucursal AND aeo.fecha_entrada BETWEEN "' . $datos["fecha_inicial"] . '" AND "' . $datos["fecha_final"] . '") AS compras';

        $query = $this
            ->from('cat_sucursales AS ct')
            ->select(
                'ct.nombre',
                'ct.id_sucursal',
                \DB::raw($ventas),
                \DB::raw($devoluciones),
                \DB::raw($compras)
            )
            ->orderBy('ventas', 'DESC');
        return $query->get();
    }*/

    public function buscarCompras($datos)
    {

        $query = $this->from("cat_sucursales as ct");

        $query->select(
            "ct.id_sucursal",
            "ct.nombre as sucursal",
            \DB::raw("ifnull((SELECT ifnull(sum(aeod.cantidad * od.precio),0) AS entrada FROM almacenes_entradas_ordenes_descripcion AS aeod LEFT JOIN almacenes_entradas_ordenes AS aeo ON aeo.id_entrada_orden = aeod.id_entrada_orden LEFT JOIN compras_ordenes_descripcion AS od ON od.id_orden_descripcion = aeod.id_orden_descripcion WHERE(aeod.id_sucursal = ct.id_sucursal) AND (date(aeo.fecha_entrada) >= '" . $datos['fecha_inicio'] . "') AND (date(aeo.fecha_entrada) <= '" . $datos['fecha_final'] . "')),0) AS entrada"),
            \DB::raw("ifnull((SELECT sum(cod.cantidad * cod.precio) AS compras FROM compras_ordenes_descripcion AS cod LEFT JOIN compras_ordenes AS co ON co.id_orden = cod.id_orden WHERE(co.id_sucursal = ct.id_sucursal) AND (date(co.fecha_orden) >= '" . $datos['fecha_inicio'] . "') AND (date(co.fecha_orden) <= '" . $datos['fecha_final'] . "') AND (co.estatus NOT IN ('cancelada', 'ignorar'))),0) AS compra"),
            \DB::raw("ifnull((SELECT sum(cof.importe) AS por_pagar FROM compras_ordenes_facturas AS cof LEFT JOIN compras_ordenes AS co ON co.id_orden = cof.id_orden WHERE (co.id_sucursal = ct.id_sucursal) AND (cof.estatus IN('pendiente'))),0) AS por_pagar"),
            \DB::raw("ifnull((SELECT ifnull(sum((ifnull((SELECT sum(aeod.cantidad) FROM almacenes_entradas_ordenes_descripcion AS aeod WHERE aeod.id_orden_descripcion = cod.id_orden_descripcion),0) - ifnull((SELECT sum(cofd.cantidad) FROM compras_ordenes_facturas_descripcion AS cofd LEFT JOIN compras_ordenes_facturas AS cof ON cof.id_orden_factura = cofd.id_orden_factura WHERE cofd.id_orden_descripcion = cod.id_orden_descripcion AND cof.estatus IN ('pendiente', 'pagada')),0)) * (cod.precio * p_iva (cod.id_producto))),0) AS por_programar FROM compras_ordenes_descripcion AS cod LEFT JOIN compras_ordenes AS co ON co.id_orden = cod.id_orden WHERE (co.estatus NOT IN ('proceso','cancelada','ignorar')) AND (cod.estatus NOT IN ('pe','cancelada','ignorar','autorizacion')) AND (date(co.fecha_orden) >= '2013-12-01') AND (co.id_sucursal = ct.id_sucursal) AND (co.programacion_pagos != 'no') AND (IF (cod.estatus = 'finalizada',(SELECT SUM(cantidad) FROM almacenes_entradas_ordenes_descripcion AS aeod WHERE aeod.id_orden_descripcion = cod.id_orden_descripcion),1) > 0)),0) AS por_programar"),
            \DB::raw("ifnull((SELECT ifnull(sum((ifnull(cod.cantidad, 0) - ifnull((SELECT sum(cofd.cantidad) FROM compras_ordenes_facturas_descripcion AS cofd LEFT JOIN compras_ordenes_facturas AS cof ON cof.id_orden_factura = cofd.id_orden_factura WHERE cofd.id_orden_descripcion = cod.id_orden_descripcion AND cof.estatus IN ('pendiente', 'pagada')),0)) * (cod.precio * p_iva (cod.id_producto))),0) AS `por_programar` FROM `compras_ordenes_descripcion` AS `cod` LEFT JOIN `compras_ordenes` AS `co` ON co.id_orden = cod.id_orden WHERE (co.estatus IN ('autorizada', 'proceso')) AND (date(co.fecha_orden) >= '2013-12-01') AND (co.id_sucursal = ct.id_sucursal) AND (IF (cod.estatus = 'finalizada',(SELECT SUM(cantidad) FROM almacenes_entradas_ordenes_descripcion AS aeod WHERE aeod.id_orden_descripcion = cod.id_orden_descripcion),1) > 0)),0) AS por_llegar"),
            \DB::raw("ifnull((SELECT(ifnull(sum(cof.importe), 0) - SUM(ifnull((SELECT sum((cod.precio * p_iva (aeod.id_producto)) * aeod.cantidad) FROM almacenes_entradas_ordenes_descripcion AS aeod LEFT JOIN compras_ordenes_descripcion AS cod ON cod.id_orden_descripcion = aeod.id_orden_descripcion WHERE aeod.id_orden = cof.id_orden),0))) AS pago_anticipado FROM compras_ordenes_facturas AS cof LEFT JOIN compras_ordenes AS co ON co.id_orden = cof.id_orden WHERE (co.id_sucursal = ct.id_sucursal) AND (date(cof.fecha_pago) >= '" . $datos['fecha_inicio'] . "') AND (date(cof.fecha_pago) <= '" . $datos['fecha_final'] . "') AND (cof.estatus = 'pagada') AND (cof.tipo_pago = 'anticipado')),0) AS pago_anticipado"),
            \DB::raw("ifnull((SELECT sum(cod.cantidad * cod.precio) AS `compras` FROM `compras_ordenes_descripcion` AS `cod` LEFT JOIN `compras_ordenes` AS `co` ON co.id_orden = cod.id_orden LEFT JOIN `productos` AS `p` ON p.id_producto = cod.id_producto LEFT JOIN `productos_familias` AS `f` ON f.id_familia = p.id_familia LEFT JOIN `cat_sucursales` AS `cs` ON cs.id_sucursal = co.id_sucursal WHERE(date(co.fecha_orden) >= '" . $datos['fecha_inicio'] . "') AND (date(co.fecha_orden) <= '" . $datos['fecha_final'] . "') AND (co.estatus NOT IN ('cancelada', 'ignorar')) AND (cod.estatus IN ('finalizada')) AND (co.id_sucursal = ct.id_sucursal)),0) AS `entradas_ordenes`")
        );

        $query->orderBy("pago_anticipado", "desc");

        //dd($query->toSql());
        return $query->get();

    }

    public function sucursalesHijoCedis($sucursal_id)
    {
        //OBTENIENDO SUCURSALES QUE SE COMUNICAN ENTRE SI

        //SUCURSALES HIJOS
        $query = $this->from('cat_sucursales_logistica as sl');
        $query->leftJoin('cat_sucursales as s', 'sl.id_sucursal_destino', '=', 's.id_sucursal');
        $query->where('id_sucursal_origen', $sucursal_id)
            ->where('estatus_logistica_sucursales', 'activo');

        $sucursalesHijo = $query->get();

        $arraySucursales = array(0);
        foreach ($sucursalesHijo as $s) {
            $arraySucursales[$s->nombre] = $s->id_sucursal_destino;
        }

        //VERIFICAMOS QUE SUCURSALES SON PADRE
        $query = $this->from('cat_sucursales_logistica as sl');
        $query->leftJoin('cat_sucursales as s', 'sl.id_sucursal_origen', '=', 's.id_sucursal');
        $query->where('id_sucursal_destino', $sucursal_id)
            ->where('estatus_logistica_sucursales', 'activo');

        $sucursalesPadre = $query->get();
        foreach ($sucursalesPadre as $s) {
//            $arraySucursales[$s->nombre] = $s->id_sucursal_origen;
        }

        return $arraySucursales;
    }

    public function obtenerSucursalBasico($datos)
    {
        $query = $this
            ->select('id_sucursal','nombre')
            ->from('cat_sucursales as s');
        $query->whereIn('id_sucursal', $datos['id_sucursal']);

        return $query->get();
    }

    public function obtenerSucursalesFacturan()
    {
        $query = $this->from('cfd_datos_emisor as sf');
        $query->leftJoin('cat_sucursales as s', 'sf.id_sucursal', '=', 's.id_sucursal');
        $query->select(
            "sf.*",
            "s.nombre");
        return $query->get();
    }
    public function banners()
    {
        return $this->belongsToMany('App\CMSBanner', 'cms_banner_sucursal', 'sucursal_id', 'cms_banner_id');
    }
}