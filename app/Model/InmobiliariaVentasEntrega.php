<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class InmobiliariaVentasEntrega extends Model
{
    protected $table = 'ventas_entregas_inmobiliaria';
    protected $fillable = ['venta_id', 'fecha_entrega', 'usuario_id'];

    public function setFechaEntregaAttribute($value)
    {
        $this->attributes['fecha_entrega'] = $value;
        $dia = Carbon::parse($value);
        if ($dia->day <= 15) {
            $this->attributes['fecha_primer_corte'] = $dia->addMonth()->startOfMonth()->toDateString();
        } else {
            $this->attributes['fecha_primer_corte'] = $dia->startOfMonth()->toDateString();
        }
    }
}