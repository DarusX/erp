<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class presupuesto_nomina_sucursal extends Model
{
    //
    protected $table = "presupuesto_nomina_sucursal";
    protected $fillable = [
        'presupuesto_id',
        'monto',
        'monto_respaldo',
        'sucursal_id',

    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "presupuesto_nomina_sucursal.sucursal_id");
        $query->leftJoin("presupuesto_nomina as n", "n.id", "=", "presupuesto_id");

        $query->select(
        #\DB::raw("0 as utilizado"),
            \DB::raw("presupuesto_nomina(s.id_sucursal,n.fecha)as utilizado"),
            "presupuesto_nomina_sucursal.*",
            "s.nombre as sucursal",
            \DB::raw("(select sum(nm.maximo) from rh_maximo_nominas as nm where id_sucursal = s.id_sucursal) as maximo"),
            \DB::raw("(WEEK(LAST_DAY(n.fecha), 5) - WEEK(DATE_SUB(LAST_DAY(n.fecha), INTERVAL DAYOFMONTH(LAST_DAY(n.fecha)) - 1 DAY), 5) + 1) as semanas"),
            "n.fecha"


        );
        if (!empty($datos["presupuesto_id"])) {
            $query->where("presupuesto_id", "=", $datos["presupuesto_id"]);
        }
//        dd($query->toSql());
        return $query->get();

    }
}
