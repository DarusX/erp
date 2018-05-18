<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlmacenesSalidasUsoInternoDescripcion extends Model
{

    protected $table = "almacenes_salidas_uso_interno_descripcion";

    protected $primaryKey = "id_salida_uso_interno_descripcion";

    public $timestamps = false;

    public function buscar($datos)
    {

        $query = $this->leftJoin("almacenes_salidas_uso_interno as aui", "aui.id_salida_uso_interno", "=", "almacenes_salidas_uso_interno_descripcion.id_salida_uso_interno");
        $query->leftJoin("productos as p", "p.id_producto", "=", "almacenes_salidas_uso_interno_descripcion.id_producto");

        $query->select(
            "almacenes_salidas_uso_interno_descripcion.*",
            "aui.fecha_aplicacion"
        );

        if (!empty($datos["id_almacen"])){

            $query->where("almacenes_salidas_uso_interno_descripcion.id_almacen", $datos["id_almacen"]);

        }

        if (!empty($datos["codigo_producto"])){

            $query->where("p.codigo_producto", $datos["codigo_producto"]);

        }

        if (!empty($datos["fecha_inicio"])){

            $query->where("aui.fecha",  ">=", $datos["fecha_inicio"]);

        }

        if (!empty($datos["fecha_final"])){

            $query->where("aui.fecha",  "<=", $datos["fecha_final"]);

        }

        return $query->get();

    }

}
