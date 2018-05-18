<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaTrabajoProductoEntregaDetalle extends Model
{
    protected $table = 'trabajos_productos_entregas_detalles_inmobiliaria';

    public function producto(){
        return $this->belongsTo(Productos::class, 'producto_id', 'id_producto');
    }
}