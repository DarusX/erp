<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ContratosSolicitantes extends Model
{

    protected $table = "contratos_solicitantes";

    protected $fillable = [
        "id_sucursal",
        "folio_solicitante",
        "empleado_captura_id",
        "fecha_captura",
        "empleado_edita_id",
        "fecha_edita"
    ];
    
    public function buscar($datos)
    {
        
        $query = $this->leftJoin("rh_empleados as ec", "ec.id_empleado", "=", "contratos_solicitantes.empleado_captura_id");
        $query->leftJoin("rh_empleados as ee", "ee.id_empleado", "=", "contratos_solicitantes.empleado_edita_id");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "contratos_solicitantes.id_sucursal");

        $query->select(
            "contratos_solicitantes.*",
            \DB::raw("ifnull(s.nombre,'S/R') as sucursal"),
            \DB::raw("ifnull(concat(ec.nombre, ' ', ec.apaterno, ' ', ec.amaterno),'S/R') as empleado_captura"),
            \DB::raw("ifnull(concat(ee.nombre, ' ', ee.apaterno, ' ', ee.amaterno),'S/R') as empleado_edita")
        );

        if (!empty($datos["id_solicitante"])){

            $query->where("id", $datos["id_solicitante"]);

        }

        if (!empty($datos["id_sucursal"])){

            $query->whereIn("contratos_solicitantes.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["first"])){

            //dd($query->toSql());
            return $query->first();

        }

        //dd($query->toSql());
        return $query->get();
        
    }

}
