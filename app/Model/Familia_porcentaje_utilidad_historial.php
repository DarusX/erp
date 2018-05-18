<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Familia_porcentaje_utilidad_historial extends Model
{
    //
    protected $table = "productos_familias_utilidad_historial";
    protected $fillable = [
        'familia_id',
        'sucursal_id',
        'empleado_id',
        'nuevo_porcentaje',
        'porcentaje',
        'fecha',
        'accion'

    ];
}
