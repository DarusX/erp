<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VentasCotizacionesValidacionesDescuentos extends Model
{

    protected $table = "ventas_cotizaciones_validaciones_descuentos";

    protected $fillable = [
        "cotizacion_id",
        "rol_id",
        "nombre_valido",
        "orden",
        "estado"
    ];

    public function buscar($datos) {


        $query = $this->from("ventas_cotizaciones_validaciones_descuentos as vv");
        $query->leftJoin("ventas_cotizaciones as vc", "vc.id", "=", "vv.cotizacion_id");
        $query->leftJoin("acl_rol as r", "r.id", "=", "vv.rol_id");

        $query->select(
            "vv.*",
            "r.rol"
        );

        if (!empty($datos["cotizacion_id"])) {

            $query->where("vv.cotizacion_id", $datos["cotizacion_id"]);

        }

        if (!empty($datos["rol_id"])) {

            $query->where("vv.rol_id", $datos["rol_id"]);

        }

        if (!empty($datos["orden_menos"])) {

            $query->where("vv.orden", "<", $datos["orden_menos"]);

        }

        if (!empty($datos["orden_mas"])) {

            $query->where("vv.orden", ">", $datos["orden_mas"]);

        }

        if (!empty($datos["orden"])) {

            $query->where("vv.orden", $datos["orden"]);

        }

        if (!empty($datos["estado"])) {

            $query->where("vv.estado", $datos["estado"]);

        }

        if (!empty($datos["first"])) {

            return $query->first();

        }

        return $query->get();

    }

}
