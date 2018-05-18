<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ViajesEnlonadasCosto extends Model
{

    protected $table = "viajes_enlonadas_costo";

    protected $primaryKey = "id_enlonada_precio";

    protected $fillable = [
        "id_enlonada_precio",
        "id_producto",
        "cantidad",
        "costo"
    ];
    
    public function buscar($datos)
    {
        
        $query = $this->leftJoin("productos as p", "p.id_producto", "=", "viajes_enlonadas_costo.id_producto");

        $query->select(
            "viajes_enlonadas_costo.*",
            "p.descripcion",
            "p.codigo_producto"
        );

        if (!empty($datos["id_producto"])){

            $query->where("viajes_enlonadas_costo.id_producto", $datos["id_producto"]);

        }

        if (!empty($datos["cantidad"])){

            $query->where("viajes_enlonadas_costo.cantidad", ">", $datos["cantidad"]);
            $query->where("viajes_enlonadas_costo.cantidad", "<", $datos["cantidad"]);

        }
        
        if (!empty($datos["first"])){
            
            return $query->first();
            
        }

        //dd($query->toSql());
        return $query->get();
        
    }

}
