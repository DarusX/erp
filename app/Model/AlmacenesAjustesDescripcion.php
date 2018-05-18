<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlmacenesAjustesDescripcion extends Model
{

    protected $table = "almacenes_ajustes_descripcion";

    protected $primaryKey = "id_ajuste_descripcion";

    public $timestamps = false;

    public function buscar($datos)
    {

        $query = $this->leftJoin("almacenes_ajustes as aa", "aa.id_ajuste", "=", "almacenes_ajustes_descripcion.id_ajuste");
        $query->leftJoin("productos as p", "p.id_producto", "=", "almacenes_ajustes_descripcion.id_producto");

        $query->select(
            "almacenes_ajustes_descripcion.*",
            "aa.fecha"
        );

        if (!empty($datos["id_sucursal"])){

            $query->where("almacenes_ajustes_descripcion.id_sucursal", "=", $datos["id_sucursal"]);

        }

        if (!empty($datos["id_almacen"])){

            $query->where("almacenes_ajustes_descripcion.id_almacen", $datos["id_almacen"]);

        }

        if (!empty($datos["codigo_producto"])){

            $query->where("p.codigo_producto", $datos["codigo_producto"]);

        }

        if (!empty($datos["fecha_inicio"])){

            $query->where("aa.fecha",  ">=", $datos["fecha_inicio"]);

        }

        if (!empty($datos["fecha_final"])){

            $query->where("aa.fecha",  "<=", $datos["fecha_final"]);

        }

        return $query->get();

    }

}
