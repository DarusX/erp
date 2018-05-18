<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class cprocedimientoActividadVideo extends Model
{
    //
    protected $table = "procedimiento_actividad_video";
    protected $fillable = [
        'video_id',
        'actividad_id',
        'procedimiento_id',


    ];

}
