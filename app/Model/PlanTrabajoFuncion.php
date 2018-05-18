<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PlanTrabajoFuncion extends Model
{
    //
    protected $table = "calidad_plan_trabajo_funcion";
    protected $fillable = [
        'plan_trabajo_id',
        'funcion_id'
    ];
}
