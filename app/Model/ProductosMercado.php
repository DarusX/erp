<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductosMercado extends Model
{
    protected $table = 'productos';
    public $timestamps = false;

    protected $primaryKey = "id_producto";

    protected $connection = "mercado";

    protected $fillable = [
        "id_familia",
        "id_categoria",
        "id_linea",
        "codigo_producto",
        "codigo_barras",
        "descripcion_corta",
        "descripcion",
        "unidad_compra",
        "unidad_venta",
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
        "imagen"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin('productos_unidades_medida as um', 'um.id_unidad_medida', '=', 'productos.unidad_compra');
        $query->leftJoin('productos_familias as pf', 'pf.id_familia', '=', 'productos.id_familia');
        $query->leftJoin('productos_categorias as pc', 'pc.id_categoria', '=', 'productos.id_categoria');
        $query->leftJoin('productos_lineas as pl', 'pl.id_linea', '=', 'productos.id_linea');
        $query->leftJoin('iva', 'iva.id_iva', '=', 'productos.id_iva');

        $query->select(
            'productos.*',
            'um.unidad_medida',
            'pf.familia',
            'pc.categoria',
            'pl.linea',
            'iva.porcentaje'
        );

        if (!empty($datos['descripcion'])) {
            $query->where('descripcion', 'like', '%' . $datos['descripcion'] . '%')->orwhere(function ($query) use ($datos) {
                $query->where('codigo_producto', 'like', '%' . $datos['descripcion'] . '%');
            });
        }
        if (!empty($datos['codigo'])) {
            $query->where('codigo_producto', $datos['codigo']);
            if (!empty($datos["first"]))
                return $query->first();
        }
        if (!empty($datos['id_producto'])) {
            $query->where('id_producto', $datos['id_producto']);
            if (!empty($datos["first"]))
                return $query->first();
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

        //dd($query->toSql());

        return $query->get();

    }

    public function buscarPrecios($datos)
    {
        $tipos_venta = \DB::select("SELECT id_tipo_venta, tipo FROM ventas_tipos");
        $query = $this->leftJoin("productos_familias as f", "f.id_familia", "=", "productos.id_familia");
        $query->leftJoin("productos_lineas as l", "l.id_linea", "=", "productos.id_linea");
        $elementos = ["productos.*", "f.familia", \DB::raw("p_costo(productos.id_producto) as costo"), \DB::raw("obtenerExistenciaSucursal(productos.id_producto, ".$datos['id_sucursal'].") as existencia")];
        foreach ($tipos_venta as $tipo) {
            $query = $query->leftJoin("productos_sucursales_precios_venta as pv" . $tipo->id_tipo_venta, function ($join) use ($tipo, $datos) {
                $join->on("pv" . $tipo->id_tipo_venta . ".id_producto", "=", "productos.id_producto");
                $join->where("pv" . $tipo->id_tipo_venta . ".id_tipo_venta", "=", $tipo->id_tipo_venta);
                $join->where("pv" . $tipo->id_tipo_venta . ".id_sucursal", "=", $datos['id_sucursal']);
            });
            $cadena = strtolower(preg_replace('[\s+]',"_",$tipo->tipo));
            array_push($elementos,\DB::raw('ifnull(pv'. $tipo->id_tipo_venta.'.precio,0) as '.$cadena));
        }

        $query->select($elementos);
        $query->groupBy("productos.id_producto");
        $query->limit(1000);

//        dd($query->toSql());


        return $query->get();
    }
}
