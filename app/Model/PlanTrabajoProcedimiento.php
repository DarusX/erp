<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PlanTrabajoProcedimiento extends Model
{
    //
    protected $table = "calidad_plan_trabajo_procedimiento";
    protected $fillable = [
        'plan_trabajo_id',
        'procedimiento_id',
        'flujo_id'
    ];

    public function nombre($datos){

        $query = $this;
        if(!empty($datos["nombre"])){
            return $query->where("nombre","like","'%".$datos["nombre"]."%'")->get();
        }
        return $query-get();
    }
}
