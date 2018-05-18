<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;

class AnalisisCompra extends Model
{
    protected $table = "almacenes_existencias";

    protected $primaryKey = "id_existencia";

    public function obtenerProductosAnalisis($params)
    {
        //SI EL ALGORITMO DE ANALISIS ES PRODUCTOS MESES CERRADOS
//        IF($params['algoritmo_analisis_compra']=='dias_anteriores'){
        //OBTENEMOS LOS PRODUCTOS
        $query = $this->from('almacenes_existencias as ae')
            ->leftJoin('productos as p', 'ae.id_producto', '=', 'p.id_producto');
        $select =
            ['ae.id_existencia', 'ae.id_sucursal', \DB::raw("obtenerSucursalNombre(ae.id_sucursal) as sucursalNombre"),
                'ae.id_almacen',
                \DB::raw("obtenerAlmacenNombre(ae.id_almacen) as almacenNombre"),
                'ae.existencia',
                \DB::raw("ventaSucursalProductoNoAtipicas(ae.id_sucursal,ae.id_producto,'" . $params['fechaInicio'] . "','" . $params['fechaFinal'] . "',0) as ventaTotal"),
                'p.id_producto', 'p.codigo_producto', 'p.descripcion', 'p.factor_conversion', 'ae.rotacion', 'ae.dep', 'p.unidad_compra', 'p.unidad_venta',
                \DB::raw('ifnull(ae.minimo_compra,0) as minimo'), \DB::raw('ifnull(ae.minimo_compra,0) as minimo_compra'), \DB::raw('ifnull(ae.maximo_compra,"") as maximo_compra'),
                \DB::raw("obtenerUnidadMedida(p.unidad_venta) as unidadMedida"),
                \DB::raw("obtenerLineaNombre(p.id_linea) as lineaNombre"),
                \DB::raw("p_costo(p.id_producto) as ultimoCosto"),
                \DB::raw("obtenerUltimaRotacionProductoSucursal(p.id_producto,ae.id_sucursal) as ventaRotacion"),
            ];

//        dd($params->ot);

        if (strtolower($params->ot) == 'si') {
            $select[] = \DB::raw("transferenciasAbastecimientoProductoAlmacen(ae.id_sucursal,ae.id_producto) as transferencias");
        }

//        if(strtolower($params->oc)=='si'){
//            $select[] =  \DB::raw("(productoComprasPendientesEntrega(ae.id_sucursal,ae.id_producto) + productoCompraSolicitudAlmacen(ae.id_almacen,ae.id_producto,'ps')) as comprasEntregar");
//
//        }
        $query->select($select);

        $query->where('ae.rotacion', '!=', 'Nula');
//        $query->where(function ($q){
//            $q->where('ae.maximo_compra','!=',0)
//                ->orWhereNull('ae.maximo_compra');
//        });
        //$query->where('ae.maximo_compra','!=',0);
        //$query->orWhereNull('ae.maximo_compra');
        if (!empty($params['id_sucursal']))
            $query->where('ae.id_sucursal', $params['id_sucursal']);
        if (!empty($params['familia_id']))
            $query->whereIn('p.id_familia', $params['familia_id']);
        if (!empty($params['linea_id']))
            $query->whereIn('p.id_linea', $params['linea_id']);
        //ACTUALIZACION FILTRO POR CODIGOS
        if (!empty($params['producto_id']))
            $query->whereIn('p.id_producto', $params['producto_id']);

        /*ACTUALIZACION, REPORTE JAT GENERAL*/
        if (!empty($params['existenciaCero']))
            $query->where('ae.existencia', '<=',0);



        $query->where(\DB::raw("productoDestino(ae.id_producto)"), '0');

        $query->orderBy('ae.rotacion', 'DESC');
//        \Log::debug($query->toSql());
//        dd($query->toSql());

        return $query->get();
    }

    public function obtenerDPE($params)
    {
        $query = $this->from('almacenes_entradas_ordenes_descripcion as eod');
        $query->leftJoin('almacenes_entradas_ordenes as e', 'eod.id_entrada_orden', '=', 'e.id_entrada_orden')
            ->leftJoin('compras_ordenes as co', 'eod.id_orden', '=', 'co.id_orden')
            ->where('eod.id_producto', $params->id_producto)
            ->where('co.id_sucursal', $params->id_sucursal);

        $select =
            [
                \DB::raw("DATEDIFF(e.fecha_entrada,co.fecha_autorizacion) as dias"),

            ];

        $query->select($select);
        $query->orderBy('eod.id_entrada_descripcion', "DESC");

//                    dd($params,$query->toSql());

        return $query->take(5)->get();

    }

    public function obtenerProductoAbastecimientoSucursales($params)
    {
//        IF($params['algoritmo_analisis_compra']=='dias_anteriores'){
        //OBTENEMOS LOS PRODUCTOS
        $query = $this->from('almacenes_existencias as ae')
            ->leftJoin('productos as p', 'ae.id_producto', '=', 'p.id_producto')
            ->leftJoin("productos_lineas as l", "l.id_linea", "=", "p.id_linea");
        $select =
            ['ae.id_existencia', 'ae.id_sucursal', \DB::raw("obtenerSucursalNombre(ae.id_sucursal) as sucursalNombre"),
                \DB::raw("obtenerAlmacenNombre(ae.id_almacen) as almacenNombre"),
                'ae.existencia',
                \DB::raw("ventaSucursalProductoNoAtipicas(ae.id_sucursal,ae.id_producto,'" . $params['fechaInicio'] . "','" . $params['fechaFinal'] . "',0) as ventaTotal"),
                'p.id_producto', 'p.codigo_producto', 'p.descripcion', 'p.factor_conversion', 'ae.rotacion', 'ae.dep', 'p.unidad_compra', 'p.unidad_venta',
                \DB::raw("obtenerUnidadMedida(p.unidad_venta) as unidadMedida"),
                //OBTENIENDO ORDEN DE LA SUCURSAL
                \DB::raw("(SELECT lo.orden FROM logisticaSucursalesAbastecimientos as lo where lo.id_sucursal_destino = " . $params['id_sucursal'] . " AND lo.id_sucursal_origen = ae.id_sucursal and estatus_logistica_sucursales in ('activo')) as ordenAbastecimiento"),
                \DB::raw("obtenerUnidadMedida(p.unidad_venta) as unidadMedida"), "l.transferencias_modificables",
                'ae.id_almacen', \DB::raw('ifnull(ae.minimo_compra,0) as minimo'), \DB::raw('ifnull(ae.minimo_compra,0) as minimo_compra'), \DB::raw('ifnull(ae.maximo_compra,0) as maximo_compra'),
            ];

        $query->select($select);

        $query->where('ae.existencia', '>', '0');
        $query->where('ae.id_sucursal', '!=', $params['id_sucursal']);
        $query->where('ae.id_producto', $params['id_producto']);
        $query->whereNotIn('ae.rotacion', $params['rotacionesDescartar']);

//        if(!empty($params['sucursalesAbastecimiento']))
//            $query->whereIn('ae.id_sucursal',$params['sucursalesAbastecimiento']);

        //DESCARTANDO SUCURSALES QUE NO FACTURAN
        $query->whereIn('ae.id_sucursal', $params['sucursalesFacturan']);

//        CONDICIONAL DE SUCURSALES PRINCIPALES
        IF ($params['sucursalesPrincipalesAbastecimiento'] == 'si')
            $query->whereRaw(\DB::raw("ae.id_sucursal IN (SELECT lo.id_sucursal_origen FROM logisticaSucursalesAbastecimientos as lo where lo.id_sucursal_origen = ae.id_sucursal AND estatus_logistica_sucursales in ('activo') AND principales in ('si'))"));
        IF ($params['sucursalesPrincipalesAbastecimiento'] == 'no')
            $query->whereRaw(\DB::raw("ae.id_sucursal IN (SELECT lo.id_sucursal_origen FROM logisticaSucursalesAbastecimientos as lo where lo.id_sucursal_origen = ae.id_sucursal AND estatus_logistica_sucursales in ('activo') AND principales in ('no'))"));


        $query->orderBy('ae.rotacion', 'ASC');
//            dd($query->toSql());
        $productosAnalizar = $query->get();

//        dd($productosAnalizar);
        $resultado = array();
        //EJECUTAMOS EL ANALISIS PARA DICHOS PRODUCTOS Y DETERMINAR SI EXISTEN EXCENDES PARA TRANSFERENCIAS

        $fechasAnalisisAnoPasado = $this->obtenerFechasAnalisisMesActualAnioPasado();

        foreach ($productosAnalizar as $k => $producto) {
            IF ($producto->minimo_compra) {
                $producto->minimo = $producto->minimo_compra;
                $productosAnalizar[$k]->minimo = $producto->minimo_compra;
//                $observaciones[] = 'Minimo producto precedido por minimo de compra';
            }
            //DESGLOZANDO LAS VARIBLES DE CALCULO
            $ventaTotalProductosHijos = 0;
            $ventasAnioPasado = $this->ventasMesActualAnioPasado(array('fechaInicio' => $fechasAnalisisAnoPasado['fechaInicio'], 'fechaFinal' => $fechasAnalisisAnoPasado['fechaTermino'], 'id_producto' => $producto->id_producto, 'id_sucursal' => $producto->id_sucursal));
            $productosAnalizar[$k]->ventasAnioPasado = $ventasAnioPasado[0]->ventaTotal;

            $ventaTotal = $producto->ventaTotal + $ventasAnioPasado[0]->ventaTotal + $ventaTotalProductosHijos;

            $pmv = round($ventaTotal / 5, 2); //Promedio Mensual de Venta.
            $productosAnalizar[$k]->pmv = $pmv;
            $pmd = round($pmv / 30, 2); //Entre 30 por se el promedio de dias de los meses
            $productosAnalizar[$k]->pmd = $pmd;

//            $ped = $this->obtenerDPE($producto);
//
//            //PROMEDIANDO
//            $total = 0;
//            FOREACH($ped as $item){
//                $total+=$item->dias;
//            }
//
//            if(count($ped))
//                $promedio = round($total/count($ped),0);
//            else
//                $promedio = 0;

//            $promedio = $producto->dep;
            $promedio = $producto->dep > 0 ? $producto->dep : 1;

            $productosAnalizar[$k]->ped = $promedio;
//                echo $producto->existencia.' --';
            $diasInventario = 0;
            if ($producto->existencia > 0 && $pmd > 0)
                $diasInventario = round($producto->existencia / $pmd, 2);
            $productosAnalizar[$k]->diasInventario = $diasInventario;

            $diasInventarioJT = round($diasInventario - $promedio, 2);
            $productosAnalizar[$k]->diasInventarioJT = $diasInventarioJT;

            $existenciaJT = round($producto->existencia - ($pmd * $promedio), 2);
            $productosAnalizar[$k]->existenciaJT = $existenciaJT;

            //INVENTARIO REQUERIDO PROYECTADO
            $existenciaRequerida = 0;
            if ($producto->rotacion == 'Alta')
                $existenciaRequerida = round($pmd * $params['rotacion_alta'], 2);
            if ($producto->rotacion == 'Media')
                $existenciaRequerida = round($pmd * $params['rotacion_media'], 2);
            if ($producto->rotacion == 'Baja')
                $existenciaRequerida = round($pmd * $params['rotacion_baja'], 2);
            if ($producto->rotacion == 'Nula con estacionalidad')
                $existenciaRequerida = round($pmd * $params['rotacion_nula con estacionalidad'], 2);

            $productosAnalizar[$k]->existenciaRequerida = $existenciaRequerida;

            $diferencial = round($existenciaJT - $existenciaRequerida, 2);
            $productosAnalizar[$k]->diferencial = $diferencial;
            $disponibleTransferencia = $existenciaJT - $existenciaRequerida;

            //DESCONTANDO EL MINIMO EN CASO DE QUE HAYA ASIGNADO EL VALOR DEL MINIMO
            if ($producto->minimo)
                $disponibleTransferencia = $existenciaJT - ($existenciaRequerida + $producto->minimo);

            if ($producto->existencia <= $producto->minimo)
                $disponibleTransferencia = 0;

//            $productosAnalizar[$k]->disponibleTransferencia = $producto->existencia - $existenciaRequerida;

//            if(!in_array($producto->unidadMedida,array("METRO","M2","MT2"))){
            $diferencial = intval($diferencial);
            $disponibleTransferencia = intval($disponibleTransferencia);
//            }ELSE{
//                //REDONDEAMOS EL DIFERENCIAL
//                $diferencial = round($diferencial,2);
//                $disponibleTransferencia = round($disponibleTransferencia,2);
//            }

            //REDONDEAMOS EL DISPONIBLE PARA TRANSFEREIR
            $productosAnalizar[$k]->sobranteFaltante = $diferencial;
            $productosAnalizar[$k]->disponibleTransferencia = $disponibleTransferencia;


        }

        $productosAnalizar = $this->burbuja($productosAnalizar);
//        dd($productosAnalizar);
        return $productosAnalizar->toArray();


//        }

    }

    public function obtenerFechasAnalisisMesAnterior()
    {
        $row = DB::select('SELECT DATE_SUB(curdate(),INTERVAL 1 DAY) as ultimoDia, DATE_SUB(curdate(),INTERVAL 90 DAY) as primerMes');
        $fechaInicio = $row[0]->primerMes;
        //$fechaInicio = $fechaInicio[0] . '-' . $fechaInicio[1] . '-01';

        $fechaFinal = $row[0]->ultimoDia;

        return array('fechaInicio' => $fechaInicio, 'fechaTermino' => $fechaFinal);


    }

    public function obtenerFechasAnalisisMesActualAnioPasado()
    {
        $row = DB::select('SELECT DATE_SUB(curdate(),INTERVAL 1 YEAR) as ultimoDia');
        $fechaInicio = $row[0]->ultimoDia;
        //$fechaInicio = $fechaInicio[0] . '-' . $fechaInicio[1] . '-01';

        $row = DB::select('SELECT DATE_ADD(DATE_SUB(curdate(),INTERVAL 1 YEAR), INTERVAL 60 DAY) as ultimoDia');
        $fechaFinal = $row[0]->ultimoDia;

        return array('fechaInicio' => $fechaInicio, 'fechaTermino' => $fechaFinal);


    }

    public function ventasMesActualAnioPasado($params)
    {
        $row = DB::select("SELECT ventaSucursalProductoNoAtipicas(" . $params['id_sucursal'] . "," . $params['id_producto'] . ",'" . $params['fechaInicio'] . "','" . $params['fechaFinal'] . "',0) as ventaTotal");
        return $row;
    }

    public function productosHijo($idProducto)
    {
        $query = $this->from('transferencias_conversiones as c');
        $query->where('c.id_producto_origen', $idProducto);
        $query->where('c.estado', 'Activo');
        $query->where(\DB::raw("productoDestino(id_producto_origen)"), '=', '0');

        return $query->get();
    }

    function burbuja($array)
    {
        $n = count($array);
        for ($i = 1; $i < $n; $i++) {
            for ($j = 0; $j < $n - $i; $j++) {
                if ($array[$j]->disponibleTransferencia < $array[$j + 1]->disponibleTransferencia) {
                    $k = $array[$j + 1];
                    $array[$j + 1] = $array[$j];
                    $array[$j] = $k;
                }
            }
        }

        return $array;
    }

    public function obtenerExistenciaProductoSucursal($id_producto, $id_sucursal)
    {
        $row = DB::select("SELECT obtenerExistenciaSucursal(" . $id_producto . "," . $id_sucursal . ") as existencia");
        return $row;
    }

    public function obtenerSolicitudes($id_producto, $id_almacen, $estatus)
    {
        $query = $this->from('compras_solicitudes as s');
        $query->where('s.id_producto', $id_producto)
            ->where('s.id_almacen', $id_almacen)
            ->where('s.estatus', $estatus)//            ->where('s.transferencia_automatica','!=',1)
        ;

        $select = ['s.id_solicitud as folio', 'fecha_solicitud as fecha', 'cantidad'];
        $query->select($select);

//        dd($query->toSql());
        return $query->get();
    }

    public function obtenerSolicitudesConsolidadas($id_producto, $id_almacen, $estatus)
    {
        $query = $this->from('compras_solicitudes as s');
        $query->where('s.id_producto', $id_producto)
            ->where('s.id_almacen', $id_almacen)
            ->where('s.estatus', $estatus)
            ->where('s.transferencia_automatica', '=', 1);

        $select = ['s.id_solicitud as folio', 'fecha_solicitud as fecha', 'cantidad'];
        $query->select($select);

//        dd($query->toSql());
        return $query->get();
    }

    public function obtenerComprasEntrega($id_producto, $id_sucursal, $dep)
    {
        /*****C O M P R A S   D I R E C T A S ******/
        $query = $this->from('compras_ordenes_descripcion as cod');
        $query->leftJoin('compras_ordenes as c', 'cod.id_orden', "=", 'c.id_orden');
        $query->where('cod.id_producto', $id_producto)
            ->where('cod.id_sucursal', $id_sucursal)
            ->whereIn('cod.estatus', array("autorizada", "autorizacion", "pe", "backorder"))
//            ->where(\DB::raw('(select count(*) from compras_ordenes_solicitudes as cods left join compras_solicitudes as s on cods.id_solicitud = s.id_solicitud where s.id_almacen != cod.id_almacen and cod.id_orden_descripcion = cods.id_orden_descripcion)',">",0))

            ->whereRaw('(select count(*) from compras_ordenes_solicitudes as cods where cod.id_orden_descripcion = cods.id_orden_descripcion)=0');
        $select = ['cod.id_orden as folio', 'c.fecha_orden as fecha', 'cantidad as cantidadComprada', 'cod.id_orden_descripcion', 'cod.id_almacen',
            \DB::raw("(cantidad - obtenerEntradaOrdenDescripcion(cod.id_orden_descripcion)) as cantidad"),
            \DB::raw("obtenerEntradaOrdenDescripcion(cod.id_orden_descripcion) as entregado"),
            \DB::raw(" IF(c.fecha_autorizacion is null,'SIN FECHA APROXIMADA',DATE(DATE_ADD(c.fecha_autorizacion,INTERVAL " . intval($dep) . " DAY))) as fecha_entrega"),
        ];
//        dd($query->toSql());
        $query->select($select);


        $ordenes = $query->get();

        //RECORREMOS PARA EXTRAER LAS SOLICITUDES QUE CONFORMAN LA PARTIDA
//        FOREACH($ordenes as $kc => $orden){
//
//            $query=$this->from('compras_ordenes_solicitudes as cods');
//            $query->leftJoin('compras_solicitudes as s','cods.id_solicitud','=','s.id_solicitud')
//                ->leftJoin('compras_ordenes_descripcion as cod','cods.id_orden_descripcion','=','cod.id_orden_descripcion')
//                ->where('cod.id_orden_descripcion','=',$orden->id_orden_descripcion);
////            $select =['cods.*'];
//
//            $select =['cods.*','s.id_almacen','cod.estatus','s.cantidad_nueva','s.id_sucursal',
//                \DB::raw("obtenerSucursalNombre(s.id_sucursal) as sucursal"),
//                \DB::raw("obtenerAlmacenNombre(s.id_almacen) as almacen"),
//                ];
//            $query->select($select);
//            $solicitudesConsolidadas = $query->get();
//
//
//
//            $bandera = false;
//            foreach($solicitudesConsolidadas as $solicitud){
//                if($solicitud->id_sucursal != $id_sucursal) {
//                    $bandera = true;
//                    break;
//                }
//            }
//
//            $ordenes[$kc]->solicituesConsolidadas = $solicitudesConsolidadas;
//            $ordenes[$kc]->tieneSolicitudesConsolidadas = $bandera;
////            //VERIFICANDO LA CANTIDAD
//            if($bandera)
//                unset($ordenes[$kc]);
//        }


        /************* C O M P R A S    I N D I R  E C T A S ********/
        $query = $this->from('compras_ordenes_solicitudes as cods');
        $query->leftJoin('compras_solicitudes as s', 'cods.id_solicitud', '=', 's.id_solicitud')
            ->leftJoin('compras_ordenes_descripcion as cod', 'cods.id_orden_descripcion', '=', 'cod.id_orden_descripcion')
            ->where('s.id_sucursal', '=', $id_sucursal)
            ->where('s.id_producto', '=', $id_producto)
//            ->where('s.transferencia_automatica','1')
            ->whereNotIn('cod.estatus', array('finalizada', 'cancelada')
            );

        $select = ['cod.id_orden_descripcion'];
        $query->select($select);
        $comprasIndirectas = $query->get();

        //APLICANDO PROCESO
        $idsComprasOrdenesDescripcion = array();
        if (count($comprasIndirectas)) {
            foreach ($comprasIndirectas as $index) {
                $idsComprasOrdenesDescripcion[] = $index->id_orden_descripcion;
            }

            $query = $this->from('compras_ordenes_descripcion as cod');
            $query->leftJoin('compras_ordenes as c', 'cod.id_orden', "=", 'c.id_orden')
                ->whereIn('cod.id_orden_descripcion', $idsComprasOrdenesDescripcion);
            $select = ['cod.id_orden as folio', 'c.fecha_orden as fecha', 'cantidad as cantidadComprada', 'cod.id_orden_descripcion',
                \DB::raw("(cantidad - obtenerEntradaOrdenDescripcion(cod.id_orden_descripcion)) as cantidad"),
                \DB::raw("obtenerEntradaOrdenDescripcion(cod.id_orden_descripcion) as entregado"),
                \DB::raw(" IF(c.fecha_autorizacion is null,'SIN FECHA APROXIMADA',DATE(DATE_ADD(c.fecha_autorizacion,INTERVAL " . intval($dep) . " DAY))) as fecha_entrega"),
            ];
            $query->select($select);


            $ordenesIndirectas = $query->get();

            if (count($ordenesIndirectas)) {

                //RECORREMOS PARA EXTRAER LAS SOLICITUDES QUE CONFORMAN LA PARTIDA
                FOREACH ($ordenesIndirectas as $kci => $orden) {

                    $query = $this->from('compras_ordenes_solicitudes as cods');
                    $query->leftJoin('compras_solicitudes as s', 'cods.id_solicitud', '=', 's.id_solicitud')
                        ->leftJoin('compras_ordenes_descripcion as cod', 'cods.id_orden_descripcion', '=', 'cod.id_orden_descripcion')
                        ->where('cod.id_orden_descripcion', '=', $orden->id_orden_descripcion)
                        ->whereNotIn('cod.estatus', array('finalizada', 'cancelada'));

                    $select = ['cods.*', 's.id_almacen', 'cod.estatus', 's.cantidad_nueva', 's.id_sucursal',
                        \DB::raw("obtenerSucursalNombre(s.id_sucursal) as sucursal"),
                        \DB::raw("obtenerAlmacenNombre(s.id_almacen) as almacen"),
                    ];
                    $query->select($select);
                    $solicitudesConsolidadas = $query->get();

                    $ordenesIndirectas[$kci]['solicitudesTransferenciasAutomaticas'] = $solicitudesConsolidadas;
                    //VERIFICANDO LA CANTIDAD
                    if (count($solicitudesConsolidadas)) {
                        $ordenesIndirectas[$kci]->consolidada = 'Si';
                        $totalOtrasSucursales = 0;
                        $totalSucursalCompra = 0;
                        foreach ($solicitudesConsolidadas as $solicitud) {
                            if ($solicitud->id_sucursal != $id_sucursal) {
                                $totalOtrasSucursales += $solicitud->cantidad_nueva;
                            } else
                                $totalSucursalCompra += $solicitud->cantidad_nueva;


                        }

                        $diferencia = ($totalSucursalCompra - $orden->entregado);
                        $ordenesIndirectas[$kci]['totalSucursalCompra'] = $totalSucursalCompra;
                        $ordenesIndirectas[$kci]['totalOtrasSucursales'] = $totalOtrasSucursales;
                        if ($diferencia <= 0) {
                            unset($ordenesIndirectas[$kci]);
                        } else {
                            $ordenesIndirectas[$kci]->cantidad = $diferencia;
                            $ordenesIndirectas[$kci]['diferencia'] = $diferencia;
                        }


                    } else {
                        $ordenesIndirectas[$kci]->consolidada = 'No';
                    }
                }


            }


        }

        if (!isset($ordenesIndirectas))
            $ordenesIndirectas = array();

        return array('ordenesDirectas' => $ordenes, 'ordenesConsolidadas' => $ordenesIndirectas);
    }

    public function obtenerTransferencias($id_producto, $id_sucursal)
    {
        $query = $this->from('transferencias as t');
        $query->leftJoin('transferencias_ordenes_descripcion as tod', 't.id_transferencia', "=", 'tod.id_transferencia');
        $query->where('t.id_producto', $id_producto)
            ->where('t.sucursal_destino', $id_sucursal)
            ->whereNull('tod.id_transferencia_descripcion')
            ->whereIn('t.estatus', array("ps", "reservado"));

        $select = ['t.id_transferencia as folio', 't.fecha_transferencia as fecha', 't.cantidad',
            \DB::raw("obtenerSucursalNombre(t.sucursal_origen) as sucursalOrigen"),
            \DB::raw("obtenerAlmacenNombre(t.almacen_origen) as almacenlOrigen"),
        ];
        $query->select($select);

//        dd($query->toSql());
        return $query->get();

    }


    public function obtenerOrdenesTransferencias($id_producto, $id_sucursal)
    {
        $query = $this->from('transferencias_ordenes_descripcion as tod');
        $query->leftJoin('transferencias_ordenes as ot', 'tod.id_transferencia_orden', "=", 'ot.id_transferencia_orden');
        $query->where('tod.id_producto', $id_producto)
            ->where('tod.sucursal_destino', $id_sucursal)
            ->whereNotIn('tod.estatus', array("finalizada", "cancelada"))
            ->whereNotIn('ot.tipo', array("ventaIndirecta"));

        $select = ['ot.id_transferencia_orden as folio', 'fecha_orden as fecha',
            \DB::raw("(cantidad - entradasOrdenesDescripcion(tod.id_transferencia_descripcion)) as cantidad"),
            \DB::raw("obtenerSucursalNombre(tod.sucursal_origen) as sucursalOrigen"),
            \DB::raw("obtenerAlmacenNombre(tod.almacen_origen) as almacenlOrigen"),
        ];
        $query->select($select);

//        dd($query->toSql());
        return $query->get();

    }

    public function ventasAtipicasProductoSucursal($id_producto, $id_sucursal, $fecha_inicio, $fecha_termino)
    {
        $query = $this->from('ventas_descripcion as vd');
        $query->where('vd.id_producto', $id_producto)
            ->where('vd.id_sucursal', $id_sucursal)
            ->where('DATE(fecha)', '>=', $fecha_inicio)
            ->where('DATE(fecha)', '<=', $fecha_termino)
            ->where('vd.ventaAtipica', "no")
            ->where(\DB::raw("ventaTieneDevolucion(vd.id_venta)=0"));

        $select = ['vd.*'];
        $query->select($select);

        $ventasAtipicas = $query->get();
        $totalVentasAtipicas = 0;
        foreach ($ventasAtipicas as $item) {
            $totalVentasAtipicas += $item['cantidad'];
        }

        return array('ventasAtipicas' => $ventasAtipicas, 'totalVentasAtipicas' => $totalVentasAtipicas);
    }

    public function obtenerSucursalesAbastecimientosPrincipales($id_sucursal)
    {
        $query = $this->from('logisticaSucursalesAbastecimientos as a');
        $query->where('a.id_sucursal_destino', $id_sucursal)
            ->where('a.principales', "si");

        $select = ['a.*'];
        $query->select($select);

        $sucursalesAbastecimiento = $query->get();
        $auxAbastecimientos = array();
        foreach ($sucursalesAbastecimiento as $item) {
            $auxAbastecimientos[] = $item['id_sucursal_origen'];
        }

        return $auxAbastecimientos;

    }

    /**ACTUALIZACION REPORTE JAT GENERAL. PRODUCTOS POR ROTACION EXISTENCIA CERO*/
    public function productosRotacionExistenciaCero($params){

        //SI EL ALGORITMO DE ANALISIS ES PRODUCTOS MESES CERRADOS
//        IF($params['algoritmo_analisis_compra']=='dias_anteriores'){
        //OBTENEMOS LOS PRODUCTOS
        $query = $this->from('almacenes_existencias as ae')
            ->leftJoin('productos as p', 'ae.id_producto', '=', 'p.id_producto');
        $select =
            ['ae.id_existencia', 'ae.id_sucursal', \DB::raw("obtenerSucursalNombre(ae.id_sucursal) as sucursalNombre"),
                'ae.id_almacen',
                \DB::raw("obtenerAlmacenNombre(ae.id_almacen) as almacenNombre"),
                'ae.existencia',
                'p.id_producto', 'p.codigo_producto', 'p.descripcion', 'p.factor_conversion', 'ae.rotacion', 'ae.dep', 'p.unidad_compra', 'p.unidad_venta',
                \DB::raw('ifnull(ae.minimo_compra,0) as minimo'), \DB::raw('ifnull(ae.minimo_compra,0) as minimo_compra'), \DB::raw('ifnull(ae.maximo_compra,"") as maximo_compra'),
                \DB::raw("obtenerUnidadMedida(p.unidad_venta) as unidadMedida"),
                \DB::raw("obtenerLineaNombre(p.id_linea) as lineaNombre"),
                \DB::raw("p_costo(p.id_producto) as ultimoCosto"),
                \DB::raw("obtenerUltimaRotacionProductoSucursal(p.id_producto,ae.id_sucursal) as ventaRotacion"),
            ];

        $query->select($select);

        $query->whereIn('ae.rotacion', array('Alta','Media','Baja'));

        if (!empty($params['id_sucursal']))
            $query->where('ae.id_sucursal', $params['id_sucursal']);
        if (!empty($params['familia_id']))
            $query->whereIn('p.id_familia', $params['familia_id']);
        if (!empty($params['linea_id']))
            $query->whereIn('p.id_linea', $params['linea_id']);
        //ACTUALIZACION FILTRO POR CODIGOS
        if (!empty($params['producto_id']))
            $query->whereIn('p.id_producto', $params['producto_id']);
        if (!empty($params['lineas_exentas']))
            $query->whereNotIn('p.id_linea', $params['lineas_exentas']);
        if (!empty($params['familias_exentas']))
            $query->whereNotIn('p.id_familia', $params['familias_exentas']);

        /*ACTUALIZACION, REPORTE JAT GENERAL*/

        $query->where('ae.existencia', '<=',0);
//        $query->whereNull('ae.maximo_compra');
//        $query->orWhere('ae.maximo_compra', '!=',0);


        $query->where(\DB::raw("productoDestino(ae.id_producto)"), '0');

        $query->orderBy('ae.rotacion', 'DESC');

        return $query->get();
    }

    public function obtenerProductosAnalisisExistenciasMayorCero($params)
    {
        //SI EL ALGORITMO DE ANALISIS ES PRODUCTOS MESES CERRADOS
//        IF($params['algoritmo_analisis_compra']=='dias_anteriores'){
        //OBTENEMOS LOS PRODUCTOS
        $query = $this->from('almacenes_existencias as ae')
            ->leftJoin('productos as p', 'ae.id_producto', '=', 'p.id_producto');
        $select =
            ['ae.id_existencia', 'ae.id_sucursal', \DB::raw("obtenerSucursalNombre(ae.id_sucursal) as sucursalNombre"),
                'ae.id_almacen',
                \DB::raw("obtenerAlmacenNombre(ae.id_almacen) as almacenNombre"),
                'ae.existencia',
                \DB::raw("ventaSucursalProductoNoAtipicas(ae.id_sucursal,ae.id_producto,'" . $params['fechaInicio'] . "','" . $params['fechaFinal'] . "',0) as ventaTotal"),
                'p.id_producto', 'p.codigo_producto', 'p.descripcion', 'p.factor_conversion', 'ae.rotacion', 'ae.dep', 'p.unidad_compra', 'p.unidad_venta',
                \DB::raw('ifnull(ae.minimo_compra,0) as minimo'), \DB::raw('ifnull(ae.minimo_compra,0) as minimo_compra'), \DB::raw('ifnull(ae.maximo_compra,"") as maximo_compra'),
                \DB::raw("obtenerUnidadMedida(p.unidad_venta) as unidadMedida"),
                \DB::raw("obtenerLineaNombre(p.id_linea) as lineaNombre"),
                \DB::raw("p_costo(p.id_producto) as ultimoCosto"),
                \DB::raw("obtenerUltimaRotacionProductoSucursal(p.id_producto,ae.id_sucursal) as ventaRotacion"),
            ];


        $query->select($select);



        if (!empty($params['id_sucursal']))
            $query->where('ae.id_sucursal', $params['id_sucursal']);
        if (!empty($params['familia_id']))
            $query->whereIn('p.id_familia', $params['familia_id']);
        if (!empty($params['linea_id']))
            $query->whereIn('p.id_linea', $params['linea_id']);
        //ACTUALIZACION FILTRO POR CODIGOS
        if (!empty($params['producto_id']))
            $query->whereIn('p.id_producto', $params['producto_id']);
        $query->where('ae.existencia', '>',0);
        if (!empty($params['lineas_exentas']))
            $query->whereNotIn('p.id_linea', $params['lineas_exentas']);
        if (!empty($params['familias_exentas']))
            $query->whereNotIn('p.id_familia', $params['familias_exentas']);

//        $query->whereNull('ae.maximo_compra');
//        $query->orWhere('ae.maximo_compra', '!=',0);
        /*ACTUALIZACION, REPORTE JAT GENERAL*/

        $query->whereIn('ae.rotacion', array('Alta','Media','Baja'));



        $query->where(\DB::raw("productoDestino(ae.id_producto)"), '0');

        $query->orderBy('ae.rotacion', 'DESC');

        return $query->get();
    }




}