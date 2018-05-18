<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductosPrecioVentaUusarios extends Model
{
    protected $table = "productos_sucursales_precios_venta_usuario";

    protected $fillable = [
        'id_usuario',
        'id_productos_sucursales_precio_venta',
        'costo_actual',
        'precio_anterior',
        'precio_nuevo',
        'utilidad_anterior',
        'utilidad_nueva',
        'fecha'
    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("productos_sucursales_precios_venta as ppv", "ppv.id_productos_sucursales_precios_venta", "=", "productos_sucursales_precios_venta_usuario.id_productos_sucursales_precio_venta");
        $query->leftJoin("usuarios as u", "u.id_usuario", "=", "productos_sucursales_precios_venta_usuario.id_usuario");
        $query->leftJoin("productos as p", "p.id_producto", "=", "ppv.id_producto");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "ppv.id_sucursal");
        $query->leftJoin("ventas_tipos as vt", "vt.id_tipo_venta", "=", "ppv.id_tipo_venta");
        $query->leftJoin("ventas_tipo_precio as t", "t.id", "=", "ppv.tipo_precio_id");

        $query->select(
            "productos_sucursales_precios_venta_usuario.*",
            "p.codigo_producto",
            "p.descripcion as producto",
            "s.nombre as sucursal",
            "u.nombre as usuario",
            \DB::raw("ifnull(vt.tipo,'') as tipo"),
            \DB::raw("ifnull(productos_sucursales_precios_venta_usuario.costo_actual,0) as costo"),
            \DB::raw("ifnull(t.tipo,'') as tipo_precio")
        );

        if(!empty($datos['id_sucursal'])){
            $query->where("ppv.id_sucursal", $datos['id_sucursal']);
        }
        if(!empty($datos['id_producto'])){
            $query->where("ppv.id_producto", $datos['id_producto']);
        }
        if(!empty($datos['id_tipo_venta'])){
            $query->where("ppv.id_tipo_venta", $datos['id_tipo_venta']);
        }
        if(!empty($datos['id_familia'])){
            $query->where("p.id_familia", $datos['id_familia']);
        }
        if(!empty($datos['id_categoria'])){
            $query->where("p.id_categoria", $datos['id_categoria']);
        }
        if(!empty($datos['id_linea'])){
            $query->where("p.id_linea", $datos['id_linea']);
        }
        if(!empty($datos['fecha_ini'])){
            $query->where("productos_sucursales_precios_venta_usuario.fecha", ">=", $datos['fecha_ini']);
        }
        if(!empty($datos['fecha_fin'])){
            $query->where("productos_sucursales_precios_venta_usuario.fecha", "<=", $datos['fecha_fin']);
        }
        if(!empty($datos["producto"])){
            $query->where("p.descripcion", "like", "%".$datos["producto"]."%")->orwhere("p.codigo_producto", "like", "%".$datos["producto"]."%");
        }

        \Log::debug($query->toSql());

        return $query->get();
    }
}
