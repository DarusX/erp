<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class TipoPrecio extends Model
{
    protected $table = 'ventas_tipo_precio';

    protected $fillable = [
        'tipo',
        'monto_minimo',
        'monto_maximo'
    ];

    public function buscar($datos){

        $query = $this;

        $query = $query->select(
            'ventas_tipo_precio.*',
            \DB::raw("ifnull(ventas_tipo_precio.monto_minimo,'0') as monto_minimo"),
            \DB::raw("ifnull(ventas_tipo_precio.monto_maximo,'0') as monto_maximo")
        );

        if(!empty($datos['tipo'])){
            $query->where('ventas_tipo_precio.tipo','like', '%' . $datos['tipo'] . '%');
        }

        return $query->get();

    }

}
