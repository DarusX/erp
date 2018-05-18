<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class verbo extends Model
{
    //
    protected $table = "calidad_verbo";
    protected $fillable = [
        'verbo',
        'empleado_captura_id',
        'estatus',
    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado", "=", "calidad_verbo.empleado_captura_id");

        if (!empty($datos["verbo"])) {
            $query->where("calidad_verbo.verbo", "like", "%" . $datos["verbo"] . "%");
        }
        if (!empty($datos["estatus"])) {
            $query->where("calidad_verbo.estatus", "=", $datos["estatus"]);
        }
        $query->select(
            "calidad_verbo.*",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as empleado_captura")

        );
        if(!empty($datos["id"])){
            $query->where("calidad_verbo.id","=",$datos["id"]);
            return $query->first();
        }

        return $query->get();
    }
}
