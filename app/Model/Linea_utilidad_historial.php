<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Linea_utilidad_historial extends Model
{
    //
    protected $table = "productos_lineas_utilidad_historial";
    protected $fillable = [
        'linea_id',
        'sucursal_id',
        'empleado_id',
        'nuevo_porcentaje',
        'porcentaje',
        'fecha',
        'accion',

    ];
}
