<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class cprocedimientoActividad extends Model
{
    //
    protected $table = "procedimiento_actividad";
    protected $fillable = [
        'actividad',
        'procedimiento_id',
        'recomendacion',

    ];

    public function buscar($datos)
    {
        $query = $this;

        if (!empty($datos["procedimiento_id"])) {
            $query = $query->where("procedimiento_id", "=", $datos["procedimiento_id"]);
        }
        $query->select("*",
            "id as key"
        );
        return $query->get();
    }
}
