<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductosPrecioBase extends Model
{
    protected $table = "productos_sucursales_precio_base";

    protected $primaryKey = "id_precio";

    protected $fillable = [
        "id_sucursal",
        "id_producto",
        "precio"
    ];

    public function buscar($datos)
    {

        $query = $this->from("productos_sucursales_precio_base as pb")->select("pb.*");

        if (!empty($datos["id_producto"]))
            $query->where("pb.id_producto", $datos["id_producto"]);

        if (!empty($datos["id_sucursal"]))
            $query->where("pb.id_sucursal", $datos["id_sucursal"]);

        if (!empty($datos["first"]))
            return $query->first();

        return $query->get();

    }

}
