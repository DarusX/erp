<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class solicitud_presupuesto extends Model
{
    //
    protected $table = "presupuesto_solicitud";
    protected $fillable = [
        'fecha',
        'total',
        'monto_respaldo',
        'empleado_captura_id',
        'tipo',
        'general',
        'empleado_autoriza_id',
        'comentario_cancelacion',
        'empleado_cancela_id',
        'created_at',
        'updated_at',
        'empleado_valida_id',
        'estatus',

    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado", "=", "empleado_captura_id");
        $query->select(
            "presupuesto_solicitud.*",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as empleado_captura"),
            \DB::raw('DATE_FORMAT(presupuesto_solicitud.fecha,"%Y") as year'),
            \DB::raw('DATE_FORMAT(presupuesto_solicitud.fecha,"%m") as month'),
            \DB::raw("presupuesto_solicitud_usado(0,presupuesto_solicitud.fecha,presupuesto_solicitud.tipo,'SI')as utilizado")
        /*`sucursal_id` integer,`fecha` date,`tipo` text,`general` text*/


        );
        if (!empty($datos["id"])) {
            $query->where("presupuesto_solicitud.id", "=", $datos["id"]);
            return $query->first();
        }
        if (!empty($datos["fecha"])) {
            $query->where("fecha", "=", $datos["fecha"]);
        }
        if (!empty($datos['year'])) {
            $query->where(\DB::raw("year(presupuesto_solicitud.fecha)"), $datos["year"]);
        }
        if (!empty($datos['month'])) {
            $query->where(\DB::raw("month(presupuesto_solicitud.fecha)"), $datos["month"]);
        }


        return $query->get();

    }


}
