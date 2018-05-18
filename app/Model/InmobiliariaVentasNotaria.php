<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaVentasNotaria extends Model
{
    protected $table = 'ventas_notarias_inmobiliaria';
    protected $fillable = ['venta_id', 'notario', 'notaria', 'fecha_firma', 'usuario_id'];
}
