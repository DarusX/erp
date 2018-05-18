<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class empleadosNotificacion extends Model
{
    //
    protected $table = "calidad_empleados_notificacion";

    public function empleado(){
        return $this->belongsTo(empleados::class,"empleado_id","id_empleado");
    }
}
