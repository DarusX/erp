<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LogisticaSucursalesAbastecimientos extends Model
{

    protected $table = "logisticaSucursalesAbastecimientos";

    protected $primaryKey = "id_logistica";

    protected $fillable = [
        "id_logistica",
        "id_sucursal_origen",
        "id_sucursal_destino",
        "orden",
        "estatus_logistica_sucursales",
        "principales"
    ];

    public $timestamps = false;

    public function buscar($datos){

        $query = $this->from("logisticaSucursalesAbastecimientos as lsa");
        $query->leftJoin("cat_sucursales as so", "so.id_sucursal", "=", "lsa.id_sucursal_origen");
        $query->leftJoin("cat_sucursales as sd", "sd.id_sucursal", "=", "lsa.id_sucursal_destino");

        $query->select(
            "lsa.*",
            "so.nombre as sucursal_origen",
            "sd.nombre as sucursal_destino"
        );

        if (!empty($datos["id_sucursal_origen"])){
            $query->where("lsa.id_sucursal_origen", $datos["id_sucursal_origen"]);
        }

        if (!empty($datos["id_sucursal_destino"])){
            $query->where("lsa.id_sucursal_destino", $datos["id_sucursal_destino"]);
        }

        if (!empty($datos["first"])){
            return $query->first();
        }

        $query->orderBy("lsa.orden", "asc");

        return $query->get();

    }

}
