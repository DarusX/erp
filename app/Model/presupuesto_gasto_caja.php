<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class presupuesto_gasto_caja extends Model
{
    //
    protected $table = "presupuesto_gasto_caja";
    protected $fillable = [
        'monto',
        'monto_respaldo',
        'fecha',
        'general',
        'empleado_captura_id',
        'empleado_valida_id',
        'empleado_autoriza_id',
        'comentario_cancelacion',
        'empleado_cancela_id',
        'estatus'


    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado", "=", "empleado_captura_id");
        $query->select(
            "presupuesto_gasto_caja.*",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as empleado_captura"),
            \DB::raw('DATE_FORMAT(presupuesto_gasto_caja.fecha,"%Y") as year'),
            \DB::raw('DATE_FORMAT(presupuesto_gasto_caja.fecha,"%m") as month'),
            \DB::raw("presupuesto_gasto_caja(presupuesto_gasto_caja.fecha,0)as utilizado")


        );
        if (!empty($datos["id"])) {
            $query->where("presupuesto_gasto_caja.id", "=", $datos["id"]);
            return $query->first();
        }
        if(!empty($datos['year'])){
            $query->where(\DB::raw("year(presupuesto_gasto_caja.fecha)"),"=",$datos["year"]);
        }
        if(!empty($datos['month'])){
            $query->where(\DB::raw("month(presupuesto_gasto_caja.fecha)"),"=",$datos["month"]);
        }
        if(!empty($datos["fecha"])){
            $query->where("fecha","=",$datos["fecha"]);
        }


        return $query->get();

    }
}
