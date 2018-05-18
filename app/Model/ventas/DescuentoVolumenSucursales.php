<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class DescuentoVolumenSucursales extends Model
{
    protected $table = 'descuentos_productos_sucursales_detalles';
    protected $fillable =['descuento_id', 'valor_minimo', 'valor_maximo', 'descuento','infinidad', 'updated_at', 'created_at', 'utilidad', 'precio', 'precio_anterior', 'utilidad_anterior','iva','precio_iva'];

    public function buscar($datos){
        $query = $this->leftJoin('descuentos_productos_sucursales as d', "d.id", "=", "descuentos_productos_sucursales_detalles.descuento_id");

        $query->select(
            'descuentos_productos_sucursales_detalles.*',
            'd.sucursal_id as sucursal',
            'd.producto_id as producto_id',
            \DB::raw('ifnull (p_costo(d.producto_id),0) as costo'),
            \DB::raw('2 as bandera')
        );

        if(!empty($datos['sucursal'])){
            $query->where('d.sucursal_id', $datos['sucursal']);
        }
        if(!empty($datos['producto_id'])){
            $query->where('d.producto_id', $datos['producto_id']);
        }
        if (!empty($datos["valor"])) {
            $query->whereRaw("'". $datos["valor"] ."' BETWEEN descuentos_productos_sucursales_detalles.valor_minimo AND descuentos_productos_sucursales_detalles.valor_maximo");
        }
        if (!empty($datos["descuento_id"])) {
            $query->where("descuentos_productos_sucursales_detalles.descuento_id", $datos["descuento_id"]);
        }
        if(!empty($datos['first'])){
            return $query->first();
        }

        return $query->get();
    }
}
