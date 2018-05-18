<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class presupuesto_nomina extends Model
{
    //
    protected $table = "presupuesto_nomina";
    protected $fillable = [
        'empleado_captura_id',
        'monto',
        'monto_respaldo',
        'empleado_valida_id',
        'empleado_autoriza_id',
        'empleado_cancela_id',
        'comentario_cancelacion',
        'fecha',
        'estatus',

    ]; 

    public function buscar($datos)
    {
        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado", "=", "empleado_captura_id");

        $query->select(
            "presupuesto_nomina.*",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as empleado_captura"),
            \DB::raw('DATE_FORMAT(presupuesto_nomina.fecha,"%Y") as year'),
            \DB::raw('DATE_FORMAT(presupuesto_nomina.fecha,"%m") as month'),
            \DB::raw("presupuesto_nomina(0,presupuesto_nomina.fecha)as utilizado")


        );

        if (!empty($datos["id"])) {
            $query->where("presupuesto_nomina.id", "=", $datos["id"]);
            return $query->first();
        }
        if(!empty($datos['year'])){
            $query->where(\DB::raw("year(presupuesto_nomina.fecha)","=",$datos["year"]));
        }
        if(!empty($datos['month'])){
            $query->where(\DB::raw("month(presupuesto_nomina.fecha)","=",$datos["month"]));
        }
        if(!empty($datos["fecha"])){
            $query->where("fecha","=",$datos["fecha"]);
        }

        return $query->get();

    }
}
