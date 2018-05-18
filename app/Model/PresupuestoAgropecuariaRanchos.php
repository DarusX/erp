<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PresupuestoAgropecuariaRanchos extends Model
{
    protected $table = "agr_presupuesto_solicitud_rancho";

    protected $fillable = [
        "presupuesto_solicitud_id",
        "rancho_id",
        "monto",
        "monto_respaldo"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("agr_presupuesto_solicitud as ps", "ps.id", "=", "agr_presupuesto_solicitud_rancho.presupuesto_solicitud_id");
        $query->leftJoin("agr_rancho as r", "r.id", "=", "agr_presupuesto_solicitud_rancho.rancho_id");

        $query->select(
            "agr_presupuesto_solicitud_rancho.*",
            "r.rancho"
        );

        if(!empty($datos["rancho_id"])){
            $query->where("agr_presupuesto_solicitud_rancho.rancho_id", $datos["rancho_id"]);
        }

        if(!empty($datos["presupuesto_solicitud_id"])){
            $query->where("agr_presupuesto_solicitud_rancho.presupuesto_solicitud_id", $datos["presupuesto_solicitud_id"]);
            if(!empty($datos["first"])){
                return $query->first();
            }
        }

        return $query->get();

    }
}
