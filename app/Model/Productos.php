<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    protected $table = 'productos';

    protected $primaryKey = "id_producto";

    protected $connection = "mysql";

    protected $fillable = [
        "id_familia",
        "id_categoria",
        "id_linea",
        "codigo_producto",
        "codigo_barras",
        "descripcion_corta",
        "descripcion",
        "clave_producto_servicio",
        "unidad_compra",
        "unidad_venta",
        "unidad_medida_sat",
        "factor_conversion",
        "negativos",
        "peso",
        "id_iva",
        "uc",
        "uv",
        "linea",
        "codigo_truper",
        "activo",
        "familia",
        "id_usuario_edito",
        "fecha_edicion",
        "garantia",
        "estatus_producto",
        "fecha_creacion",
        "usuario_creo",
        "id_usuario_creo",
        "clasificacion",
        "master",
        "pagina",
        "imagen",
        "tipo_factor_conversion",
        "actualizacion_precios",
        "estatus_utilidad",
        "rotacion"
    ];

    public function promociones()
    {
        return $this->belongsToMany('App\Model\ventas\Promociones', 'ventas_promociones_productos');
    }

    public function buscarDescuento($datos)
    {
        $query = $this->leftJoin('productos_unidades_medida as um', 'um.id_unidad_medida', '=', 'productos.unidad_compra');
        $query->leftJoin('productos_familias as pf', 'pf.id_familia', '=', 'productos.id_familia');
        $query->leftJoin('productos_categorias as pc', 'pc.id_categoria', '=', 'productos.id_categoria');
        $query->leftJoin('productos_lineas as pl', 'pl.id_linea', '=', 'productos.id_linea');
        $query->leftJoin('iva', 'iva.id_iva', '=', 'productos.id_iva');
        $query->leftJoin('productos_sucursales_precios_venta as pv', function ($join) use ($datos) {
            $join->on("pv.id_producto", "=", "productos.id_producto")
                ->where("pv.id_sucursal", "=", $datos['sucursal'])
                ->where("pv.tipo_precio_id", "=", "1");

        });
        $query->leftJoin('descuentos_productos_sucursales as d', function ($join) use ($datos) {
            $join->on("d.producto_id", "=", "productos.id_producto")
                ->where("d.sucursal_id", "=", $datos['sucursal']);
        });


        $select = [
            'productos.codigo_producto',
            'productos.descripcion',
            'productos.id_producto',
            'um.unidad_medida',
            'pf.familia',
            'pc.categoria',
            'pl.linea',
            'iva.porcentaje',
            'd.estado as estado_descuento',
            'd.id as descuento_id',
            \DB::raw("p_costo(productos.id_producto) as precio_base"),
            \DB::raw("ifnull(productos.codigo_truper,'') as codigo_truper"),
            \DB::raw("ifnull(productos.factor_conversion,'') as factor_conversion"),
            'pv.precio as precio_venta'
        ];

        if (!isset($datos['empresa']) || (isset($datos['empresa']) && $datos['empresa'] == "mysql")) {
            array_push($select,
                \DB::raw("ifnull(productos.tipo_factor_conversion,'') as tipo_factor_conversion"),
                \DB::raw('(SELECT COUNT(*) FROM productos_porcentaje_utilidad AS ppu LEFT JOIN productos_porcentajes_utilidades_ediciones AS ppue ON ppue.producto_porcentaje_utilidad_id = ppu.id WHERE ppu.producto_id = productos.id_producto AND ppue.estado = "Pendiente") AS utilidades_pendientes'),
                \DB::raw('(SELECT COUNT(*) FROM productos_porcentaje_utilidad AS ppu LEFT JOIN productos_porcentajes_utilidades_ediciones AS ppue ON ppue.producto_porcentaje_utilidad_id = ppu.id WHERE ppu.producto_id = productos.id_producto AND ppue.estado = "Validado") AS utilidades_validadas')
            );
        }

        $query->select($select);

        if (!empty($datos['descripcion'])) {
            $query->where('descripcion', 'like', '%' . $datos['descripcion'] . '%')->orwhere(function ($query) use ($datos) {
                $query->where('codigo_producto', 'like', '%' . $datos['descripcion'] . '%');
            });
        }
        if (!empty($datos['codigo'])) {
            $query->where('codigo_producto', $datos['codigo']);
            if (!empty($datos["first"])) {
                return $query->first();
            }
        }
        if (!empty($datos['id_producto'])) {
            $query->where('productos.id_producto', $datos['id_producto']);
            if (!empty($datos["first"])) {
                return $query->first();
            }
        }
        if (!empty($datos['estatus_producto'])) {
            $query->where('estatus_producto', $datos['estatus_producto']);
        }
        if (!empty($datos['clasificacion'])) {
            $query->where('clasificacion', $datos['clasificacion']);
        }
        if (!empty($datos['familia'])) {
            $query->where('productos.id_familia', $datos['familia']);
        }
        if (!empty($datos['linea'])) {
            $query->where('pl.id_linea', $datos['linea']);
        }
        if (!empty($datos['categoria'])) {
            $query->where('pc.id_categoria', $datos['categoria']);
        }
        if (!empty($datos['codigo_truper'])) {
            $query->where('codigo_truper', $datos['codigo_truper']);
            if (!empty($datos["first"])) {
                return $query->first();
            }
        }

        if (!empty($datos['activo'])) {
            $query->where('activo', $datos['activo']);
        }

        if (!empty($datos['descontinuado'])) {
            if ($datos['descontinuado'] == "no") {
                $query->where('productos.id_familia', '!=', 18);
            } else {
                $query->where('productos.id_familia', 18);
            }
        }

        return $query->get();

    }

    public function buscar($datos)
    {

        $query = $this->leftJoin('productos_unidades_medida as um', 'um.id_unidad_medida', '=', 'productos.unidad_compra');
        $query->leftJoin('productos_unidades_medida as ums', 'ums.id_unidad_medida', '=', 'productos.unidad_medida_sat');
        $query->leftJoin('productos_familias as pf', 'pf.id_familia', '=', 'productos.id_familia');
        $query->leftJoin('productos_categorias as pc', 'pc.id_categoria', '=', 'productos.id_categoria');
        $query->leftJoin('productos_lineas as pl', 'pl.id_linea', '=', 'productos.id_linea');
        $query->leftJoin('iva', 'iva.id_iva', '=', 'productos.id_iva');

        $select = [
            'productos.*',
            'um.unidad_medida',
            'pf.familia',
            'pc.categoria',
            'pl.linea',
            'iva.porcentaje',
            \DB::raw("p_costo(productos.id_producto) as precio_base"),
            \DB::raw("ifnull(productos.codigo_truper,'') as codigo_truper"),

            \DB::raw("ifnull(productos.factor_conversion,'') as factor_conversion"),
            \DB::raw("ifnull(productos.minimo,0) as minimo"),
            \DB::raw("ifnull(p_costo(productos.id_producto),0) as ultimoCosto"),
            \DB::raw("obtenerValidacionesCosto(productos.id_producto) as validaciones"),
            \DB::raw("if(productos.activo,'Si','No') as activo_fijo"),
            \DB::raw("ifnull(productos.factor_conversion,'') as factor_conversion"),
            \DB::raw("ifnull(productos.clave_producto_servicio,'') as clave_producto_servicio"),
            \DB::raw("ifnull(productos.unidad_medida_sat,'') as id_unidad_medida_sat"),
            \DB::raw("ifnull(ums.unidad_medida,'') as unidad_medida_sat"),
            \DB::raw("ifnull(ums.clave_sat,'') as clave_medida_sat"),
            \DB::raw("ifnull(productos.peso,0) as peso"),
            \DB::raw("ifnull(ums.clave_sat,'') as clave_medida_sat"),
            \DB::raw("ifnull(productos.peso,0) as peso")
        ];

        if (!isset($datos['empresa']) || (isset($datos['empresa']) && $datos['empresa'] == "mysql")) {
            array_push($select,
                \DB::raw("ifnull(productos.tipo_factor_conversion,'') as tipo_factor_conversion"),
                \DB::raw('(SELECT COUNT(*) FROM productos_porcentaje_utilidad AS ppu LEFT JOIN productos_porcentajes_utilidades_ediciones AS ppue ON ppue.producto_porcentaje_utilidad_id = ppu.id WHERE ppu.producto_id = productos.id_producto AND ppue.estado = "Pendiente") AS utilidades_pendientes'),
                \DB::raw('(SELECT COUNT(*) FROM productos_porcentaje_utilidad AS ppu LEFT JOIN productos_porcentajes_utilidades_ediciones AS ppue ON ppue.producto_porcentaje_utilidad_id = ppu.id WHERE ppu.producto_id = productos.id_producto AND ppue.estado = "Validado") AS utilidades_validadas')
            );
        }

        if (!empty($datos["pendientes_rol"])) {

            array_push($select, \DB::raw("(SELECT IF ((SELECT COUNT(*) FROM validaciones_costos_productos AS vcp LEFT JOIN modificacion_costo_producto AS mcp ON mcp.id = vcp.modificacion_costo_producto_id LEFT JOIN administrador_validaciones AS av ON av.id = vcp.administrador_validaciones_id WHERE vcp.orden = (vcp1.orden - 1) AND vcp.estado = 'pendiente' AND mcp.producto_id = 54) > 0,'no','si') FROM validaciones_costos_productos AS vcp1 LEFT JOIN modificacion_costo_producto AS mcp1 ON mcp1.id = vcp1.modificacion_costo_producto_id LEFT JOIN administrador_validaciones AS av1 ON av1.id = vcp1.administrador_validaciones_id WHERE mcp1.producto_id = productos.id_producto AND vcp1.estado = 'pendiente' AND av1.rol_id = " . $datos["pendientes_rol"] . " LIMIT 1) as rolValidacion"));

        }

        if (!empty($datos["sucursal_id"])) {

            array_push($select, \DB::raw("productoTieneKits(productos.id_producto," . $datos["sucursal_id"] . ") as tieneKits"));
            array_push($select, \DB::raw("obtenerExistenciaSucursal(productos.id_producto," . $datos["sucursal_id"] . ") as existencia"));
        }

        if (!empty($datos["porcentajes"]) && $datos["porcentajes"] == "si")
            array_push($select, \DB::raw("if((SELECT COUNT(id) FROM productos_porcentaje_utilidad AS ppue WHERE ppue.producto_id = productos.id_producto AND ppue.porcentaje > 0) > 0,'si','no') AS productos_porcentajes"));

        $query->select($select);

        if (!empty($datos['descripcion'])) {
            $query->where('descripcion', 'like', '%' . $datos['descripcion'] . '%')->orwhere(function ($query) use ($datos) {
                $query->where('codigo_producto', 'like', '%' . $datos['descripcion'] . '%');
            });
        }
        if (!empty($datos['codigo'])) {
            $query->where('codigo_producto', $datos['codigo']);
            if (!empty($datos["first"])) {
                return $query->first();
            }
        }
        if (!empty($datos['id_producto'])) {
            $query->where('id_producto', $datos['id_producto']);
            if (!empty($datos["first"])) {
                return $query->first();
            }
        }
        if (!empty($datos['estatus_producto'])) {
            $query->where('estatus_producto', $datos['estatus_producto']);
        }
        if (!empty($datos['clasificacion'])) {
            $query->where('clasificacion', $datos['clasificacion']);
        }
        if (!empty($datos['familia'])) {
            if (count($datos["familia"]) > 1) {
                $query->whereIn('productos.id_familia', $datos['familia']);
            } else {
                $query->where('productos.id_familia', $datos['familia']);
            }
        }
        if (!empty($datos['linea'])) {
            $query->where('productos.id_linea', $datos['linea']);
        }
        if (!empty($datos['categoria'])) {
            $query->where('productos.id_categoria', $datos['categoria']);
        }
        if (!empty($datos['codigo_truper'])) {
            $query->where('codigo_truper', $datos['codigo_truper']);
            if (!empty($datos["first"])) {
                return $query->first();
            }
        }
        if (!empty($datos["validacion_pendiente"])) {

            if ($datos["validacion_pendiente"] == "si") {

                $query->having("validaciones", ">", "0");

            }

        }
        if (!empty($datos["pendientes_rol"])) {

            $query->having('rolValidacion', '=', 'si');

        }

        if (!empty($datos["actualizacion_precios"])) {

            $query->where("productos.actualizacion_precios", "=", $datos["actualizacion_precios"]);

        }

        if (!empty($datos["limite"])) {

            $query->limit($datos["limite"]);

        }

        if (!empty($datos["codigos"])) {

            $query->whereIn("productos.codigo_producto", $datos["codigos"]);

        }

        if (!empty($datos["porcentajes"])) {

            $query->havingRaw("productos_porcentajes = '". $datos["porcentajes"] ."'");

        }

        //dd($query->toSql());

        return $query->get();

    }

    public function buscarPrecios($datos)
    {
        $tipos_venta = \DB::select("SELECT id_tipo_venta, tipo FROM ventas_tipos");
        $query = $this->leftJoin("productos_familias as f", "f.id_familia", "=", "productos.id_familia");
        $query->leftJoin("productos_lineas as l", "l.id_linea", "=", "productos.id_linea");
        $elementos = ["productos.*", "f.familia", \DB::raw("p_costo(productos.id_producto) as costo"), \DB::raw("obtenerExistenciaSucursal(productos.id_producto, " . $datos['id_sucursal'] . ") as existencia")];
        foreach ($tipos_venta as $tipo) {
            $query = $query->leftJoin("productos_sucursales_precios_venta as pv" . $tipo->id_tipo_venta, function ($join) use ($tipo, $datos) {
                $join->on("pv" . $tipo->id_tipo_venta . ".id_producto", "=", "productos.id_producto");
                $join->where("pv" . $tipo->id_tipo_venta . ".id_tipo_venta", "=", $tipo->id_tipo_venta);
                $join->where("pv" . $tipo->id_tipo_venta . ".id_sucursal", "=", $datos['id_sucursal']);
            });
            $cadena = strtolower(preg_replace('[\s+]', "_", $tipo->tipo));
            array_push($elementos, \DB::raw('ifnull(pv' . $tipo->id_tipo_venta . '.precio,0) as ' . $cadena));
        }

        $query->select($elementos);
        $query->groupBy("productos.id_producto");
        //$query->limit(1000);

//        dd($query->toSql());


        return $query->get();
    }

    public function precioBase()
    {
        return $this->hasOne(PSPB::class, 'id_producto', 'id_producto');
    }

    public function buscarRotacion($datos)
    {

        $query = $this->from("productos as p");
        $query->leftJoin("almacenes_existencias as ae", "ae.id_producto", "=", "p.id_producto");
        $query->leftJoin("productos_lineas as l", "l.id_linea", "=", "p.id_linea");
        $query->leftJoin("productos_familias as f", "f.id_familia", "=", "p.id_familia");

        $query->select(
            "p.codigo_producto",
            "p.descripcion",
            "p.id_producto",
            \DB::raw("(select sum(vd.cantidad) from ventas_descripcion as vd where vd.fecha >= '" . $datos["fecha_inicio"] . "' and vd.fecha <= '" . $datos["fecha_fin"] . "' and p.id_producto = vd.id_producto and vd.id_sucursal = " . $datos["id_sucursal"] . ") as total"),
            \DB::raw("(select sum(e.existencia) from almacenes_existencias as e where id_producto = p.id_producto and e.id_sucursal = " . $datos["id_sucursal"] . ") as existencia"),
            \DB::raw("(select psp.precio from productos_sucursales_precio_base as psp where psp.id_producto = p.id_producto limit 1) as costo"),
            \DB::raw("(select pv.precio from productos_sucursales_precios_venta as pv where pv.id_producto = p.id_producto and pv.id_tipo_venta = 1 limit 1) as precio1"),
            "l.linea",
            "f.familia"
        );

        if (!empty($datos["id_sucursal"])) {

            $query->where("ae.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["id_familia"])) {

            $query->whereIn("p.id_familia", $datos["id_familia"]);

        }

        if (!empty($datos["id_linea"])) {

            $query->whereIn("p.id_linea", $datos["id_linea"]);

        }

        $query->groupBy("p.codigo_producto");

        $query->orderBy("total", "desc");

        //dd($query->toSql());
        return $query->get();

    }

    public function buscarNulos($datos)
    {

        $query = $this->from("productos as p")->select(
            "p.id_producto",
            "p.codigo_producto",
            "p.descripcion",
            "f.familia",
            "l.linea",
            \DB::raw("obtenerExistenciaProducto(p.id_producto) AS existencia"),
            \DB::raw("p_costo(p.id_producto) AS costo"),
            \DB::raw("ifnull((SELECT vd.fecha FROM ventas_descripcion AS vd WHERE vd.id_producto = p.id_producto ORDER BY vd.fecha DESC LIMIT 1),'Sin venta') AS ufv"),
            //\DB::raw("ifnull((SELECT vd.fecha FROM ventas_descripcion AS vd WHERE vd.id_producto = p.id_producto AND EXTRACT( YEAR_MONTH FROM vd.fecha ) = '". \Carbon\Carbon::now()->format('Y-m') ."' ORDER BY vd.fecha DESC LIMIT 1),'Sin venta') AS ufv"),
            \DB::raw("ifnull(((SELECT costo) * (SELECT existencia)),0) AS valor_inventario")
        )
            ->leftJoin("productos_familias as f", "f.id_familia", "=", "p.id_familia")
            ->leftJoin("productos_lineas as l", "l.id_linea", "=", "p.id_linea");

        if (!empty($datos["id_familia"])) {

            $query->whereIn("p.id_familia", $datos["id_familia"]);

        }

        if (!empty($datos["id_linea"])) {

            $query->whereIn("p.id_linea", $datos["id_linea"]);

        }

        if (!empty($datos["rotacion"])) {

            $query->havingRaw("comprobarRotacionProducto(p.id_producto,'" . $datos["rotacion"] . "')");

        } else {

            $query->havingRaw("comprobarRotacionProducto(p.id_producto,null)");

        }

        if (!empty($datos["hijos"])) {

            $query->whereNotIn("p.id_producto", $datos["hijos"]);

        }

        if (!empty($datos["cero"])) {

            if ($datos["cero"] == "no") {

                $query->having("existencia", ">", 0);

            }

        }

        $query->where('p.activo', 0)
            ->where('p.clasificacion', 'venta')
            ->orderBy("existencia", "desc");

        //dd($query->toSql());

        return $query->get();

    }

    public function buscarUtilidad($producto, $sucursal, $precio)
    {

        $query = $this->from("productos as p");

        $query->select(
            \DB::raw("buscarUtilidadTipoPrecio(" . $producto . ", " . $sucursal . ", " . $precio . ") as utilidad")
        );

        return $query->first();

    }

    public function busquedaVenta($params)
    {
        $query = $this->from('productos AS p')
            ->select(
                'p.codigo_producto',
                'p.id_producto'
            );

        if (!empty($params['busqueda'])) {
            $query = $query->where('codigo_producto', $params['busqueda'])
                ->orWhere('codigo_barras', $params['busqueda']);
        }

        return $query->first();
    }


    public function unidadMedidaVenta()
    {
        return $this->belongsTo(UnidadesMedida::class, 'unidad_venta');
    }


}