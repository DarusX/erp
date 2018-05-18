<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class CotizacionesVenta extends Model
{
    public function cotizacion()
    {
        return $this->belongsTo(VentasCotizaciones::class, 'cotizacion_id');
    }
}