<?php

namespace App\Model\Finanzas;

use Illuminate\Database\Eloquent\Model;

class GastosDescripcion extends Model
{
    protected $table = 'agr_gastos_descripcion';

    protected $fillable = [
        'id',
        'gasto_id',
        'cantidad',
        'descripcion',
        'precio_unitario',
        'importe'
    ];

    public function buscar($datos){

        $query = $this->select(
            'agr_gastos_descripcion.*'
        );

        if(!empty($datos['id'])){
            $query->where('gasto_id', $datos['id']);
        }

        return $query->get();
    }
}
