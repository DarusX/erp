<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VehiculosCostoViajes extends Model
{

    protected $table = "vehiculos_costo_viajes";

    protected $primaryKey = "id_vehiculo_costo_viaje";

    protected $fillable = [
        "id_sucursal_origen",
        "id_sucursal_destino",
        "costo",
        "descripcion",
        "peso_minimo",
        "peso_maximo"
    ];

    public function buscar($datos)
    {

        $query = $this->select(
            "vehiculos_costo_viajes.*"
        );

        if (!empty($datos["id_sucursal_origen"])){
            $query->where("id_sucursal_origen", $datos["id_sucursal_origen"]);
        }

        if (!empty($datos["id_sucursal_destino"])){
            $query->where("id_sucursal_destino", $datos["id_sucursal_destino"]);
        }

        if (!empty($datos["peso"])){
            $query->whereRaw($datos["peso"] ." >= peso_minimo");
            $query->whereRaw($datos["peso"] ." <= peso_maximo");
        }

        if (!empty($datos["first"])){
            return $query->first();
        }

        //dd($query->toSql());

        return $query->get();

    }

}
