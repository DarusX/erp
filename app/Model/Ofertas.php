<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Ofertas extends Model
{
    protected $table = "ofertas";

    protected $primaryKey = "id_oferta";

    protected $fillable = [
        "id_sucursal",
        "id_linea",
        "id_producto",
        "codigo_producto",
        "precio",
        "descuento",
        "f1",
        "f2",
        "imagen",
        "pagina",
        "id_empleado_registra",
        "fecha_registra",
        "estatus",
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("productos as p", "p.id_producto", "=", "ofertas.id_producto");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "ofertas.id_sucursal");
        $query->leftJoin("productos_lineas as l", "l.id_linea", "=", "ofertas.id_linea");
        $query->leftJoin("productos_familias as f", "f.id_familia", "=", "p.id_familia");
        $query->leftJoin("productos_categorias as c", "c.id_categoria", "=", "p.id_categoria");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "ofertas.id_empleado_registra");
        $query->leftJoin("productos_sucursales_precios_venta as pv", function ($join) {
            $join->on("pv.id_producto", "=", "ofertas.id_producto");
            $join->on("pv.id_sucursal", "=", "ofertas.id_sucursal");
            $join->where("pv.id_tipo_venta", "=", 5);
        });
        $query->leftJoin("productos_sucursales_precios_venta as pv2", function ($join) {
            $join->on("pv2.id_producto", "=", "ofertas.id_producto");
            $join->on("pv2.id_sucursal", "=", "ofertas.id_sucursal");
            $join->where("pv2.id_tipo_venta", "=", 1);
        });
  
        $select = [
            "ofertas.*",
            "p.descripcion",
            "s.nombre as sucursal",
            "l.linea",
            "f.familia",
            "c.categoria",
            \DB::raw("concat(e.nombre, ' ', e.apaterno, ' ', e.amaterno) as nombre_completo"),
            \DB::raw("ifnull(pv.precio,0) as precio_venta"),
            \DB::raw("ifnull(pv2.precio,0) as precio_venta_actual"),
            "pv2.id_productos_sucursales_precios_venta as precio_venta_id",
            "pv2.respaldo_precio"
        ];

        $query->select(
            $select
        );

        if (!empty($datos['id_oferta'])) {
            $query->where("id_oferta", $datos["id_oferta"]);
            if (!empty($datos['first'])) {
                return $query->first();
            }
        }
        if (!empty($datos['id_producto'])) {
            $query->where("ofertas.id_producto", $datos['id_producto']);
        }
        if (!empty($datos['id_sucursal'])) {
            $query->where("ofertas.id_sucursal", $datos['id_sucursal']);
        }
        if (!empty($datos['id_linea'])) {
            $query->where("ofertas.id_linea", $datos['id_linea']);
        }
        if (!empty($datos['id_familia'])) {
            $query->where("f.id_familia", $datos['id_familia']);
        }
        if (!empty($datos['id_categoria'])) {
            $query->where("c.id_categoria", $datos['id_categoria']);
        }
        if (!empty($datos['estatus'])) {
            $query->where("ofertas.estatus", "=", $datos['estatus']);
        }
        if (!empty($datos['estatus_not_in'])) {
            $query->whereNotIn("ofertas.estatus", $datos['estatus_not_in']);
        }
        if (!empty($datos['fecha_ini'])) {
            $query->where(\DB::raw("date(f1)"), ">=", $datos['fecha_ini']);
        }
        if (!empty($datos['fecha_fin'])) {
            $query->where(\DB::raw("date(f2)"), "<=", $datos['fecha_fin']);
        }

        if (!empty($datos['fecha_inicio'])) {
            $query->where("ofertas.f1", "<=", $datos['fecha_inicio']);
            $query->where("ofertas.f2", ">", $datos['fecha_inicio']);
        }
        if (!empty($datos['fecha_termino'])) {
            $query->where("ofertas.f2", "<", $datos['fecha_termino']);
        }
        if (!empty($datos['precio_venta'])) {
            $query->whereNotNull("pv2.id_productos_sucursales_precios_venta");
        }
//        $query->where("ofertas.id_producto", 19138);

//        dd($query->toSql());
        return $query->get();

    }
}
