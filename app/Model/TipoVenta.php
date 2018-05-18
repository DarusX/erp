<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TipoVenta extends Model
{
    protected $table = 'agr_tipo_venta';

    protected $fillable = [
        'id',
        'tipo_venta'
    ];
}
