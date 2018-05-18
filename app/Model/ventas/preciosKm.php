<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class preciosKm extends Model
{
    protected $table ='ventas_precios_km';
    protected $fillable = ['peso_inicial', 'peso_final', 'precio','sucursal_id', 'created_at', 'updated_at','infinidad'];

    public function buscar($datos){

        $query = $this->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "ventas_precios_km.sucursal_id");

        $query->select(
            'ventas_precios_km.*',
            's.nombre',
            's.mapa as coordenadas',
            \DB::raw('2 as bandera')
        );

        if(!empty($datos['sucursal_id'])){
            $query->where('ventas_precios_km.sucursal_id', $datos['sucursal_id']);
        }


        return $query->get();

    }
}
