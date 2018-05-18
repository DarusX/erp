<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductosPrecioVenta extends Model
{
    protected $table = "productos_sucursales_precios_venta";
    protected $primaryKey = "id_productos_sucursales_precios_venta";

    protected $fillable = [
        "id_sucursal",
        "id_producto",
        "id_tipo_venta",
        "tipo_precio_id",
        "ancla",
        "precio",
        "respaldo_precio",
        "comodin"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin('productos as p', 'p.id_producto', '=', 'productos_sucursales_precios_venta.id_producto');
        $query->leftJoin('ventas_tipos as vt', 'vt.id_tipo_venta', '=', 'productos_sucursales_precios_venta.id_tipo_venta');
        $query->leftJoin('cat_sucursales as s', 's.id_sucursal', '=', 'productos_sucursales_precios_venta.id_sucursal');
        $query->leftJoin('ventas_tipo_precio as tp', 'tp.id', '=', 'productos_sucursales_precios_venta.tipo_precio_id');

        $query->select(
            'productos_sucursales_precios_venta.*',
            \DB::raw('ifnull(productos_sucursales_precios_venta.precio,0) as precio_actual'),
            \DB::raw('ifnull(vt.tipo,"") as tipo_venta'),
            'tp.tipo',
            \DB::raw("p_costo(p.id_producto) as ultimoCosto"),
            \DB::raw("ifnull(productos_sucursales_precios_venta.precio,0) as precio")
        );

        if (!empty($datos['id_producto'])) {
            $query->where('productos_sucursales_precios_venta.id_producto', $datos['id_producto']);
        }
        if (!empty($datos['id_sucursal'])) {
            $query->where("productos_sucursales_precios_venta.id_sucursal", $datos['id_sucursal']);
        }
        if (!empty($datos['id_tipo_venta'])) {
            $query->where("productos_sucursales_precios_venta.id_tipo_venta", $datos['id_tipo_venta']);
        }
        if (!empty($datos['tipo_precio_id'])) {
            $query->where("productos_sucursales_precios_venta.tipo_precio_id", $datos['tipo_precio_id']);
        }
        if (!empty($datos["ver_tipos"])) {
            $query->whereNotNull("productos_sucursales_precios_venta.tipo_precio_id");
        }
        if (isset($datos["tipos_venta"])) {
            $query->whereNotNull("productos_sucursales_precios_venta.id_tipo_venta");
        }
        if (!empty($datos["id_familia"])) {

            if (count($datos["id_familia"]) > 0) {

                $query->whereIn("p.id_familia", $datos["id_familia"]);

            } else {

                $query->where("p.id_familia", $datos["id_familia"]);

            }

        }
        if (!empty($datos["id"])) {

            $query->where("productos_sucursales_precios_venta.id_productos_sucursales_precios_venta", $datos["id"]);

        }
        if (!empty($datos['first'])) {

            return $query->first();
        }

        return $query->get();

    }
}
