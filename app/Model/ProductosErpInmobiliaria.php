<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductosErpInmobiliaria extends Model
{
    protected $table = "productos_erp_inmo";

    protected $primaryKey = "id_producto_erp_inmo";

    protected $fillable = [
        "id_producto",
        "id_producto_inmo"
    ];

    public function buscar($datos){

        $query = $this->leftJoin("productos as p", "p.id_producto", "=", "productos_erp_inmo.id_producto");

        $query->select(
            "productos_erp_inmo.*",
            "p.codigo_producto"
        );

        if (!empty($datos["id_producto"])){

            $query->where("productos_erp_inmo.id_producto", $datos["id_producto"]);

        }

        if (!empty($datos["id_producto_inmo"])){

            $query->where("productos_erp_inmo.id_producto_inmo", $datos["id_producto_inmo"]);

        }

        if (!empty($datos["first"])){

            return $query->first();

        }

        return $query->get();

    }
}
