<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class region extends Model
{
    //
    protected $table = "calidad_region";
    protected $fillable = [
        'region',
        'empleado_captura_id',
        'empleado_cancela_id',
        'fecha_cancelacion',
        'estatus',
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado", "=", "calidad_region.empleado_captura_id");
        $query->leftJoin("rh_empleados as e2","e2.id_empleado", "=", "calidad_region.empleado_cancela_id");

        $query->select(
            "calidad_region.*",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as nombre_completo")

        );
        if (!empty($datos["region"])) {
            $query->where("region", "like", "%" . $datos["region"] . "%");
        }
        if (!empty($datos["region_id"])) {
            $query->where("calidad_region.id", "=", $datos["region_id"]);
            return $query->first();
        }
        //dd($query->toSql());
        return $query->get();
    }
}
