<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class observacionComentario extends Model
{
    //
    protected $table = "calidad_observacion_comentario";
    protected $fillable = [
        'observacion_id',
        'empleado_id',
        'comentario',
        'tipo'
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado","=","calidad_observacion_comentario.empleado_id");

        if(isset($datos["observacion_id"])){
            $query->where("calidad_observacion_comentario.observacion_id","=",$datos["observacion_id"]);
        }

        $query->select(
            "calidad_observacion_comentario.*",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as nombre_captura")

        );
        if(isset($datos["id"])){
            $query->where("calidad_observacion_comentario.id","=",$datos["id"]);
            return $query->first();
        }

        return $query->get();
    }

}
