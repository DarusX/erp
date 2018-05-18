<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaGarantiasProductosEntregasDetalles extends Model
{
    protected $table = 'garantias_productos_entregas_detalles_inmobiliaria';

    public function producto()
    {
        return $this->belongsTo(Productos::class, 'producto_id', 'id_producto');
    }
}