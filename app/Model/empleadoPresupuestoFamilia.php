<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class empleadoPresupuestoFamilia extends Model
{
    //
    protected $table = "sucursales_familias_empleados_presupuestos";
    protected $fillable = [
        'id_empleado',
        'id_sucursal',
        'id_familia',
        'fecha',
        'meta',
        'meta_respaldo',
        'porcentaje',
        'bono',
        'nuevo_bono',
        'estatus',
        'created_at',
        'updated_at',
        'empleado_captura_id',
        'empleado_autoriza_id',
        'empleado_valida_id',
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado", "=", "sucursales_familias_empleados_presupuestos.id_empleado");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "e.id_sucursal");
        $query->leftJoin("productos_familias as f", "f.id_familia", "=", "sucursales_familias_empleados_presupuestos.id_familia");

        $query->select([
            "sucursales_familias_empleados_presupuestos.*",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as nombre_completo"),
            "s.nombre as sucursal",
            "f.familia"
        ]);

        if (!empty($datos["id_sucursal"])) {
            $query->where("sucursales_familias_empleados_presupuestos.id_sucursal", "=", $datos["id_sucursal"]);
        }
        if (!empty($datos["nombre_empleado"])) {
            $query->where(\DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno)"), "like", "%" . $datos["nombre_empleado"] . "%");

        }
        if (!empty($datos["id_empleado"])) {
            $query->where("e.id_empleado", "=", $datos["id_empleado"]);
        }
        if (!empty($datos["fecha_inicio"])) {
            $query->where("sucursales_familias_empleados_presupuestos.fecha", ">=", $datos["fecha_inicio"]);
        }
        if (!empty($datos["fecha_fin"])) {
            $query->where("sucursales_familias_empleados_presupuestos.fecha", "<=", $datos["fecha_termino"]);
        }
        if (!empty($datos["id_sucursal_familia_empleado"])) {
            $query->where("sucursales_familias_empleados_presupuestos.id_sucursal_familia_empleado", "=", $datos["id_sucursal_familia_empleado"]);

            return $query->first(); 
        }


        return $query->get();
    }

}
