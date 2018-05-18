<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class DescuentoVolumen extends Model
{
    protected $table = 'descuentos_productos_sucursales';
    protected $fillable = ['sucursal_id', 'producto_id', 'estado', 'created_at', 'updated_at'];

    public function buscar($datos){
        $query = $this->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "descuentos_productos_sucursales.sucursal_id");
        $query->leftJoin("productos as p", "p.id_producto", "=", "descuentos_productos_sucursales.producto_id");

        $query->select(
          'descuentos_productos_sucursales.*',
          's.nombre as sucursal',
          'p.codigo_producto',
          'p.descripcion'
        );

        if(!empty($datos['producto_id'])){
            $query->where('descuentos_productos_sucursales.producto_id', $datos['producto_id']);
        }

        if(!empty($datos['sucursal_id'])){
            $query->where('descuentos_productos_sucursales.sucursal_id', $datos['sucursal_id']);
        }

        if (!empty($datos["estado"])){
            $query->where("descuentos_productos_sucursales.estado", $datos["estado"]);
        }

        if(!empty($datos['first'])){
          return $query->first();
        }

        return $query->get();

    }

}
