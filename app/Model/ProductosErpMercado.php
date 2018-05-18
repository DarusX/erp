<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductosErpMercado extends Model
{
    protected $table = "productos_erp_mercado";

    protected $primaryKey = "id_producto_erp_mercado";

    protected $fillable = [
        "id_producto",
        "id_producto_mercado"
    ];

    public function buscar($datos){

        $query = $this->leftJoin("productos as p", "p.id_producto", "=", "productos_erp_mercado.id_producto");

        $query->select(
            "productos_erp_mercado.*",
            "p.codigo_producto"
        );

        if (!empty($datos["id_producto"])){

            $query->where("productos_erp_mercado.id_producto", $datos["id_producto"]);

        }

        if (!empty($datos["id_producto_mercado"])){

            $query->where("productos_erp_mercado.id_producto_mercado", $datos["id_producto_mercado"]);

        }

        if (!empty($datos["first"])){

            return $query->first();

        }

        return $query->get();

    }

}
