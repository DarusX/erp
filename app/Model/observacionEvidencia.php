<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class observacionEvidencia extends Model
{
    //
    protected $table = "calidad_observacion_evidencia";
    protected $fillable = [
        'observacion_id',
        'comentario_id',
        'empleado_id',
        'size',
        'type',
        'nombre',
        'ruta'

    ];
}
