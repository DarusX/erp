<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class presupuestoUtilidadEmpleado extends Model
{
    //
    protected $table = "sucursales_empleados_presupuestos_utilidad";
    protected $fillable = [
        'empleado_id',
        'sucursal_id',
        'fecha',
        'utilidad_inicial',
        'nuevo_utilidad_inicial',
        'utilidad_final',
        'nuevo_utilidad_final',
        'bono',
        'nuevo_bono',
        'estatus',

    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "sucursales_empleados_presupuestos_utilidad.sucursal_id");

        $select = [
            'sucursales_empleados_presupuestos_utilidad.id',
            'sucursales_empleados_presupuestos_utilidad.empleado_id',
            'sucursales_empleados_presupuestos_utilidad.fecha',
            'sucursales_empleados_presupuestos_utilidad.utilidad_inicial',
            'sucursales_empleados_presupuestos_utilidad.utilidad_final',
            'sucursales_empleados_presupuestos_utilidad.bono',
            'sucursales_empleados_presupuestos_utilidad.nuevo_utilidad_inicial',
            'sucursales_empleados_presupuestos_utilidad.nuevo_utilidad_final',
            'sucursales_empleados_presupuestos_utilidad.nuevo_bono',
            'sucursales_empleados_presupuestos_utilidad.estatus',
            \DB::raw("ifnull(sucursal_id,'general') as sucursal_id"),
            \DB::raw("ifnull(s.nombre,'General') as sucursal")

        ];
        $query->select($select);


        if (!empty($datos["empleado_id"])) {
            $query->where("empleado_id", "=", $datos["empleado_id"]);
        }
        if (!empty($datos["sucursal_id"])) {
            $query->where("sucursal_id", "=", $datos["sucursal_id"]);
        }
//        dd($query->toSql());

        return $query->get();
    }
}
