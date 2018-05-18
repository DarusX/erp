<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class observacionFuncion extends Model
{
    //
    protected $table = "calidad_observacion_funcion";
    protected $fillable = [
        "observacion_id",
        "funcion_id"

    ];
}
