<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class entregasFletes extends Model
{
    protected $table = 'ventas_cotizaciones_fletes';
    protected $fillable = ['cotizacion_id', 'sucursal_id','coordenadas_entrega', 'direccion_entrega', 'coordenadas_salida', 'precio', 'peso', 'distancia', 'ruta'];

    public function buscar($datos){
        $query = $this->leftJoin('cat_sucursales as s', 's.id_sucursal', '=', 'ventas_cotizaciones_fletes.sucursal_id');

        $query->select(
            'ventas_cotizaciones_fletes.*',
            's.nombre'
        );

        if(!empty($datos['cotizacion_id'])){
            $query->where('ventas_cotizaciones_fletes.cotizacion_id', $datos['cotizacion_id']);
        }

        if(!empty($datos['id'])){
            $query->where('ventas_cotizaciones_fletes.id', $datos['id']);
        }

        if(!empty($datos['first'])){
            return $query->first();
        }

        return $query->get();

    }
}
