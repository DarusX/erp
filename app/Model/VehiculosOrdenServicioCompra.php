<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VehiculosOrdenServicioCompra extends Model
{
    protected $table = "vehiculos_orden_servicio_compra";

    protected $primaryKey = "id_orden_servicio_compra";

    protected $fillable = [
        "id_orden_servicio",
        "id_orden"
    ];

    public function buscar($datos)
    {

        $query = $this->from("vehiculos_orden_servicio_compra as vosc")->select(
            "vosc.*"
        );

        if (!empty($datos["id_orden"])){

            $query->where("id_orden", $datos["id_orden"]);

        }

        return $query->first();

    }

}
