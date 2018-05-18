<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class notificacion extends Model
{
    //
    protected $table = "calidad_notificaciones";
    protected $fillable = [
        "titulo",
        "mensaje",
        "empleado_id",
        "empleado_captura_id",
        "estatus"
    ];
    public function empleado (){
        return $this->belongsTo(empleados::class,"empleado_id","id_empleado");
    }
    public function empleadoCaptura(){
        return $this->belongsTo(empleados::class,"empleado_captura_id","id_empleado");
    }

}
