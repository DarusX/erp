<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class presupuesto_gasto_compra extends Model
{
    //
    protected $table = "presupuesto_gasto_compra";
    protected $fillable = [
        'clasificacion_gasto_id',
        'monto',
        'monto_respaldo',
        'fecha',
        'general',
        'empleado_captura_id',
        'empleado_autoriza_id',
        'comentario_cancelacion',
        'empleado_cancela_id',
        'empleado_valida_id',
        'estatus',

    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado", "=", "empleado_captura_id");
        $query->leftJoin("gastosClasificacion as gc","clasificacion_gasto_id","=","gc.id_gasto_clasificacion");
        $query->select(
            "presupuesto_gasto_compra.*","gc.clasificacion",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as empleado_captura"),
            \DB::raw('DATE_FORMAT(presupuesto_gasto_compra.fecha,"%Y") as year'),
            \DB::raw('DATE_FORMAT(presupuesto_gasto_compra.fecha,"%m") as month'),
            \DB::raw("presupuesto_compra_usado(presupuesto_gasto_compra.fecha,presupuesto_gasto_compra.clasificacion_gasto_id,0)as utilizado")


        );
        if (!empty($datos["id"])) {
            $query->where("presupuesto_gasto_compra.id", "=", $datos["id"]);
            return $query->first();
        }

        if (!empty($datos['year'])) {
            $query->where(\DB::raw("year(presupuesto_gasto_compra.fecha)"), "=", $datos["year"]);
        }
        if (!empty($datos['month'])) {
            $query->where(\DB::raw("month(presupuesto_gasto_compra.fecha)"), "=", $datos["month"]);
        }
        if (!empty($datos["clasificacion_gasto_id"])) {
            $query->where("presupuesto_gasto_compra.clasificacion_gasto_id", "=", $datos["clasificacion_gasto_id"]);
        }
        if (!empty($datos["fecha"])) {
            $query->where("fecha", "=", $datos["fecha"]);
        }

        return $query->get();
    }
}
