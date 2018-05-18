<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VehiculosRecargasCombustible extends Model
{

    protected $table = "vehiculos_recargas_combustibles";

    protected $primaryKey = "id_vehiculo_carga";

    public function buscar($datos)
    {

        $query = $this;

        $query->select(
            "vehiculos_recargas_combustibles.*"
        );

        if (!empty($datos["id_sucursal"])){

            $query->where("vehiculos_recargas_combustibles.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["fecha_inicio"])){

            $query->where("vehiculos_recargas_combustibles.fecha_recarga",  ">=", $datos["fecha_inicio"]);

        }

        if (!empty($datos["fecha_final"])){

            $query->where("vehiculos_recargas_combustibles.fecha_recarga",  "<=", $datos["fecha_final"]);

        }

        return $query->get();

    }

}
