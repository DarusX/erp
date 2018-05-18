<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VentaDescripcionBaja extends Model
{
    protected $table = 'agr_ventas_descripcion_baja';

    protected $fillable = [
        'id',
        'venta_descripcion_id',
        'animal_baja_id'
    ];
}
