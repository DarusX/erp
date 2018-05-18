<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PlanTrabajoEvidencia extends Model
{
    //
    protected $table = "calidad_plan_trabajo_evidencia";
    protected $fillable = [
        'plan_trabajo_id',
        'complemento_id',
        'empleado_id',
        'size',
        'type',
        'nombre',
        'ruta'

    ];
}
