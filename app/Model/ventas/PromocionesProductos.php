<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class PromocionesProductos extends Model
{
    protected $table = 'ventas_promociones_productos';
    protected $fillable = ['producto_id', 'descuento_id', 'precio', 'utilidad','precio_anterior','utilidad_anterior','descuento', 'sucursal_id','created_at', 'updated_at'];

    public function buscar($datos){
        $query = $this->leftJoin('ventas_promociones_descuentos as d', 'd.id', '=', 'ventas_promociones_productos.descuento_id');
        $query->leftJoin('productos as p', 'p.id_producto','=','ventas_promociones_productos.producto_id');
        $query->leftJoin('cat_sucursales as s', 's.id_sucursal', '=', 'ventas_promociones_productos.sucursal_id');

        $query->select(
            'ventas_promociones_productos.*',
            \DB::raw('2 as bandera'),
            's.nombre as sucursal_nombre',
            \DB::raw('CONCAT(p.codigo_producto, " - ", p.descripcion) as codigo_descripcion'),
            'p.id_producto as producto_id'
        );

        if(!empty($datos['descuento_id'])){
            $query->where('ventas_promociones_productos.descuento_id', $datos['descuento_id']);
        }
        if (!empty($datos["producto"])){
            $query->where("ventas_promociones_productos.producto_id", $datos["producto"]);
        }
        if (!empty($datos["fecha_actual"])){
            $query->whereRaw("'".$datos["fecha_actual"]."' BETWEEN d.fecha_inicial AND d.fecha_final");
        }
        if (!empty($datos["first"])){
            return $query->first();
        }

        return $query->get();

    }
}
