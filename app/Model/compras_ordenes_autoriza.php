<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class compras_ordenes_autoriza extends Model
{
    protected $table = "compras_ordenes_usuario_autoriza_edicion";

    protected $fillable = [
        'id_orden',
        'id_usuario_autoriza',
        'movimiento',
        'fecha_movimiento'
    ];
}
