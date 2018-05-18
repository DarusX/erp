<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class empleadoPrecioUtilidad extends Model
{
    //
    protected $table = "sucursales_familias_empleados_presupuestos_utilidad";
    protected $fillable = [
        'presupuesto_id',
        'utilidad_inicial',
        'utilidad_final',
        'nuevo_utilidad_inicial',
        'nuevo_utilidad_final',
        'bono',
        'nuevo_bono',

    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("sucursales_familias_empleados_presupuestos as sp", "sp.id_sucursal_familia_empleado", "=", "sucursales_familias_empleados_presupuestos_utilidad.presupuesto_id");

        $select = [
            "sucursales_familias_empleados_presupuestos_utilidad.*"
        ];

        $query->select($select);

        if (!empty($datos["presupuesto_id"])) {
            $query->where("presupuesto_id", "=", $datos["presupuesto_id"]);
        }
        return $query->get();

        //$query = $this->leftJoin("rh_empleados as e","e.id_empleado","=","")
    }
}
