<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VentasPromediosSucursalesHistorial extends Model
{
    protected $table = 'ventas_promedios_sucursales_historial';
    protected $fillable = ['venta_promedio_sucursal_id', 'sucursal_id', 'producto_id', 'promedio', 'porcentaje', 'promedio_porcentaje', 'tendencia_registro_anterior'];
}