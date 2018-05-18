<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class observacionProcedimiento extends Model
{
    //
    protected $table = "calidad_observacion_procedimiento";
    protected $fillable = [
        'observacion_id',
        'procedimiento_id',
        'flujo_id'

    ];
}
