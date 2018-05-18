<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TrabajosRecurrentesDetalles extends Model
{
    protected $table = 'agr_trabajos_recurrentes_detalles';

    protected $fillable = [
        'id',
        'trabajo_id',
        'trabajo_id',
        'trabajo_recurrente_id'
    ];
}
