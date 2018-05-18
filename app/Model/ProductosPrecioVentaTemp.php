<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductosPrecioVentaTemp extends Model
{
    protected $table = "productos_sucursales_precios_venta_temp";

    protected $fillable = [
        "token",
        "productos_sucursales_precios_venta_id",
        "sucursal_id",
        "producto_id",
        "tipo_precio_id",
        "precio_actual",
        "precio_nuevo",
        "utilidad_actual",
        "utilidad_nueva"
    ];

    public function buscar ($datos)
    {

        $query = $this->from("productos_sucursales_precios_venta_temp as pv")
            ->leftJoin("productos as p", "p.id_producto", "=", "pv.producto_id")
            ->leftJoin("ventas_tipo_precio as t", "t.id", "=", "pv.tipo_precio_id")

            ->select(
                "pv.*",
                "p.codigo_producto",
                "p.descripcion",
                "t.tipo",
                \DB::raw("p_costo(p.id_producto) as costo")
            );

        if (!empty($datos["id"])) {

            $query->where("pv.id", $datos["id"]);

        }

        if (!empty($datos["token"])) {

            $query->where("pv.token", $datos["token"]);

        }

        if (!empty($datos["producto_id"])) {

            $query->where("pv.producto_id", $datos["producto_id"]);

        }

        if (!empty($datos["sucursal_id"])) {

            $query->where("pv.sucursal_id", $datos["sucursal_id"]);

        }

        if (!empty($datos["tipo_precio_id"])) {

            $query->where("pv.tipo_precio_id", $datos["tipo_precio_id"]);

        }

        if (!empty($datos["first"])) {

            return $query->first();

        }

        return $query->get();

    }

}
