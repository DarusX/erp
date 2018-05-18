<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaVentasAvaluo extends Model
{
    protected $table = 'ventas_avaluos_inmobiliaria';
    protected $fillable = ['empresa', 'valuador', 'monto', 'diferencia', 'usuario_id', 'venta_id'];
}