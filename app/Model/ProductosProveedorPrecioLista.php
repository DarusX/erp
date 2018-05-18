<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductosProveedorPrecioLista extends Model
{

    protected $table = "productos_proveedores_precios_lista";

    protected $fillable = [
        "producto_id",
        "proveedor_id",
        "precio_lista"
    ];

    public function buscar($datos)
    {

        $query = $this->from("productos_proveedores_precios_lista as pppl")->select(
            \DB::raw("ifnull(pppl.precio_lista,0) as precio_lista")
        );

        if (!empty($datos["id_producto"])){

            $query->where("pppl.producto_id", $datos["id_producto"]);

        }

        if (!empty($datos["id_proveedor"])){

            $query->where("pppl.proveedor_id", $datos["id_proveedor"]);

        }

        return $query->first();

    }

}
