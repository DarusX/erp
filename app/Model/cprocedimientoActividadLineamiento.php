<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class cprocedimientoActividadLineamiento extends Model
{
    //
    protected $table = "procedimiento_actividad_lineamiento";
    protected $fillable = [
        'lineamiento',
        'actividad_id',
        'procedimiento_id',


    ];
    public function buscar($datos){
        $query = $this;

        if($datos["actividad_id"]){
            $query = $query->where("actividad_id","=",$datos["actividad_id"]);
        }
        $query->select("*","id as key");

        return $query->get();
    }
}
