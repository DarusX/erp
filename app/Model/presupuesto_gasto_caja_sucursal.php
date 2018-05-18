<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class presupuesto_gasto_caja_sucursal extends Model
{
    //
    protected $table = "presupuesto_gasto_caja_sucursal";
    protected $fillable = [
        'presupuesto_id',
        'sucursal_id',
        'monto',
        'monto_respaldo'

    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "sucursal_id");
        $query->leftJoin("presupuesto_gasto_caja as pgc","pgc.id","=","presupuesto_id");

        $query->select("presupuesto_gasto_caja_sucursal.*",
            "s.nombre as sucursal",
            \DB::raw("(presupuesto_gasto_caja(pgc.fecha,s.id_sucursal)) as utilizado")
        );
        if (!empty($datos["presupuesto_id"])) {
            $query->where("presupuesto_gasto_caja_sucursal.presupuesto_id", "=", $datos["presupuesto_id"]);
        }



        return $query->get();
    }
}
