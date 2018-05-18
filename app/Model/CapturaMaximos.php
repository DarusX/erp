<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CapturaMaximos extends Model
{

    protected $table = "captura_maximos";

    protected $fillable = [
        "id_sucursal",
        "id_empleado_captura",
        "fecha_captura",
        "id_empleado_valida",
        "fecha_valida",
        "id_empleado_autoriza",
        "fecha_autoriza",
        "id_empleado_cancela",
        "fecha_cancela",
        "estatus"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "captura_maximos.id_sucursal");
        $query->leftJoin("rh_empleados as ec", "ec.id_empleado", "=", "captura_maximos.id_empleado_captura");
        $query->leftJoin("rh_empleados as ev", "ev.id_empleado", "=", "captura_maximos.id_empleado_valida");
        $query->leftJoin("rh_empleados as ea", "ea.id_empleado", "=", "captura_maximos.id_empleado_autoriza");
        $query->leftJoin("rh_empleados as ecan", "ecan.id_empleado", "=", "captura_maximos.id_empleado_cancela");

        $query->select(
            "captura_maximos.*",
            "s.nombre as sucursal",
            \DB::raw("ifnull(concat(ec.nombre,' ',ec.apaterno,' ',ec.amaterno),'S/R') as empleado_captura"),
            \DB::raw("ifnull(concat(ev.nombre,' ',ev.apaterno,' ',ev.amaterno),'S/R') as empleado_valida"),
            \DB::raw("ifnull(concat(ea.nombre,' ',ea.apaterno,' ',ea.amaterno),'S/R') as empleado_autoriza"),
            \DB::raw("ifnull(concat(ecan.nombre,' ',ecan.apaterno,' ',ecan.amaterno),'S/R') as empleado_cancela")
        );

        if (!empty($datos["id_sucursal"])){
            $query->whereIn("captura_maximos.id_sucursal", $datos["id_sucursal"]);
        }

        if (!empty($datos["id"])){
            $query->where("captura_maximos.id", $datos["id"]);
            if (!empty($datos["first"])){
                return $query->first();
            }
        }

        if (!empty($datos["fecha_ini"])){
            $query->where("captura_maximos.fecha_captura", ">=", $datos["fecha_ini"]);
        }

        if (!empty($datos["fecha_fin"])){
            $query->where("captura_maximos.fecha_captura", "<=", $datos["fecha_fin"]);
        }

        if (!empty($datos["estatus"])){
            $query->where("captura_maximos.estatus", $datos["estatus"]);
        }

        //dd($query->toSql());
        return $query->get();

    }

}
