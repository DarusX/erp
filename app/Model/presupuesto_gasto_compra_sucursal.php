<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class presupuesto_gasto_compra_sucursal extends Model
{
    //
    protected $table = "presupuesto_gasto_compra_sucursal";
    protected $fillable = [
        'presupuesto_id',
        'sucursal_id',
        'monto',
        'monto_respaldo'

    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "sucursal_id");
        $query->leftJoin("presupuesto_gasto_compra as pg", "pg.id", "=", "presupuesto_id");

        $select = [
            "presupuesto_gasto_compra_sucursal.*",
            "s.nombre as sucursal",
            \DB::raw("presupuesto_compra_usado(pg.fecha,pg.clasificacion_gasto_id,presupuesto_gasto_compra_sucursal.sucursal_id) as utilizado")
        ];
        $query->select($select);
        if (!empty($datos["presupuesto_id"])) {
            $query->where("presupuesto_gasto_compra_sucursal.presupuesto_id", "=", $datos["presupuesto_id"]);
        }
        if (!empty($datos["sucursal_id"])) {
            $query->where("presupuesto_gasto_compra_sucursal.sucursal_id", "=", $datos["sucursal_id"]);
            return $query->first();
        }

        return $query->get();
    }
}
