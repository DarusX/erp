<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlmacenesSalidas extends Model
{

    protected $table = "almacenes_salidas";

    protected $primaryKey = "id_salida";

    public $timestamps = false;

    public function buscar($datos)
    {

        $query = $this->leftJoin("productos as p", "p.id_producto", "=", "almacenes_salidas.id_producto");
        $query->leftJoin("ventas as v", "v.id_venta", "=", "almacenes_salidas.id_venta");

        $query->select(
            "almacenes_salidas.*",
            \DB::raw("ifnull(almacenes_salidas.cantidad,0) as cantidad_nueva"),
            "v.fecha as fecha_venta"
        );

        if (!empty($datos["id_almacen"])){

            $query->where("almacenes_salidas.id_almacen", $datos["id_almacen"]);

        }

        if (!empty($datos["codigo_producto"])){

            $query->where("p.codigo_producto", $datos["codigo_producto"]);

        }

        if (!empty($datos["fecha_inicio"])){

            $query->where("almacenes_salidas.fecha",  ">=", $datos["fecha_inicio"]);

        }

        if (!empty($datos["fecha_final"])){

            $query->where("almacenes_salidas.fecha",  "<=", $datos["fecha_final"]);

        }

        return $query->get();

    }

}
