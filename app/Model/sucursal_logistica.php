<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class sucursal_logistica extends Model
{
    //
    protected $table = "cat_sucursales_logistica";
    protected $primaryKey = "id_sucursal_logistica";

    protected $fillable = [
        "id_sucursal_origen",
        "id_sucursal_destino",
        "estatus_logistica_sucursales"
    ];

    public $timestamps = false;

    public function buscar($datos)
    {
        $query = $this;
        $query = $query->where("id_sucursal_origen", $datos["id_sucursal_origen"]);
        $query = $query->where("id_sucursal_destino", $datos["id_sucursal_destino"]);

        return $query->first();
    }

    public function obtenerDatos($datos)
    {

        $query = $this->from("cat_sucursales_logistica as csl");
        $query->leftJoin("cat_sucursales as so", "so.id_sucursal", "=", "csl.id_sucursal_origen");
        $query->leftJoin("cat_sucursales as sd", "sd.id_sucursal", "=", "csl.id_sucursal_destino");

        $query->select(
            "csl.*",
            "so.nombre as sucursal_origen",
            "sd.nombre as sucursal_destino"
        );

        if (!empty($datos["id_sucursal_origen"])){

            $query->where("csl.id_sucursal_origen", $datos["id_sucursal_origen"]);

        }

        if (!empty($datos["id_sucursal_destino"])){

            $query->where("csl.id_sucursal_destino", $datos["id_sucursal_destino"]);

        }

        if (!empty($datos["estatus"])){

            $query->where("csl.estatus_logistica_sucursales", $datos["estatus"]);

        }

        return $query->get();

    }

}
