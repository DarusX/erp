<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class solicitud_presupuesto_sucursal extends Model
{
    //
    protected $table = "presupuesto_solicitud_sucursal";
    protected $fillable = [
        'presupuesto_solicitud_id',
        'sucursal_id',
        'monto',

    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "presupuesto_solicitud_sucursal.sucursal_id");

        if (!empty($datos["presupuesto_solicitud_id"])) {
            $query->where("presupuesto_solicitud_id", "=", $datos["presupuesto_solicitud_id"]);
        }


        $query->select(
            \DB::raw("presupuesto_solicitud_usado(s.id_sucursal,'" . $datos["fecha"] . "','" . $datos["tipo"] . "','NO')as utilizado"),
            "presupuesto_solicitud_sucursal.*",
            "s.nombre as sucursal"

        );
        //dd($query->toSql(), $datos);


        return $query->get();

    }

}
