<?php

namespace App\Model;

use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model;

class InmobiliariaGarantiasProductos extends Model
{
    protected $table = 'garantias_productos_inmobiliaria';

    public function producto()
    {
        return $this->belongsTo(Productos::class, 'producto_id', 'id_producto');
    }

    public function buscar($datos)
    {
        $query = $this->select(
            'garantias_productos_inmobiliaria.*',
            'p.codigo_producto',
            'p.descripcion'
        )
            ->leftJoin('garantias_levantamientos_inmobiliaria AS gli', 'gli.id', '=', 'garantias_productos_inmobiliaria.garantia_levantamiento_id')
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'garantias_productos_inmobiliaria.producto_id');
        //->where('gli.estado', 'Autorizado');
        //->where('garantias_productos_inmobiliaria.garantia_id', $datos['garantia_id']);

        if (isset($datos['levantamiento_id'])) {
            $query = $query->where('garantias_productos_inmobiliaria.garantia_levantamiento_id', $datos['levantamiento_id']);
        }

        if (isset($datos['garantia_id'])) {
            $query = $query->where('garantias_productos_inmobiliaria.garantia_id', $datos['garantia_id']);
        }
        //$query = $query->groupBy('garantias_productos_inmobiliaria.garantia_levantamiento_id');
        return $query->get();
    }
}