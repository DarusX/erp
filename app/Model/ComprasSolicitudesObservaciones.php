<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ComprasSolicitudesObservaciones extends Model
{

    protected $table = "compras_solicitudes_observaciones";

    protected $fillable = [
        "id_solicitud",
        "observacion"
    ];

    public function buscar($datos)
    {

        $query = $this->from("compras_solicitudes_observaciones as cso");

        $query->select(
            "cso.*"
        );

        if (!empty($datos["id_solicitud"])){

            $query->where("cso.id_solicitud", $datos["id_solicitud"]);

        }

        return $query->get();

    }

}
