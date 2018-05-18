<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ContratosProductos extends Model
{

    protected $table = "contratos_productos";

    protected $fillable = [
        "contrato_id",
        "producto_id",
        "estatus"
    ];

    public function buscar($datos){

        $query = $this->leftJoin("contratos as c", "c.id", "=", "contratos_productos.contrato_id");
        $query->leftJoin("productos as p", "p.id_producto", "=", "contratos_productos.producto_id");

        $query->select(
            "contratos_productos.*",
            "p.codigo_producto",
            "p.descripcion"
        );

        if (!empty($datos["contrato_id"])){

            $query->where("contratos_productos.contrato_id", $datos["contrato_id"]);

        }

        if (!empty($datos["producto_id"])){

            $query->where("contratos_productos.producto_id", $datos["producto_id"]);

        }

        if (!empty($datos["id"])){

            $query->where("contratos_productos.id", $datos["id"]);

        }

        if (!empty($datos["first"])){
            
            return $query->first();

        }

        return $query->get();

    }
}
