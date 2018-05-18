<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class PromocionesSucursales extends Model
{
    protected $table = 'ventas_promociones_sucursales';
    protected $fillable = ['descuento_id', 'sucursal_id', 'created_at', 'updated_at'];

    public function buscar($datos){
        $query = $this->leftJoin('ventas_promociones_descuentos as d', 'd.id', '=', 'ventas_promociones_sucursales.descuento_id');
        $query->leftJoin('cat_sucursales as s', 's.id_sucursal', '=', 'ventas_promociones_sucursales.sucursal_id');

        $query->select(
            'ventas_promociones_sucursales.*',
            's.*'
        );

        if(!empty($datos['descuento_id'])){
            $query->where('ventas_promociones_sucursales.descuento_id', $datos['descuento_id']);
        }

        if(!empty($datos['sucursal_id'])){
            $query->where('ventas_promociones_sucursales.sucursal_id', $datos['sucursal_id']);
        }


        return $query->get();

    }
}
