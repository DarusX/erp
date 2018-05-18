<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlmacenesEntradasDevolucionesDescripcion extends Model
{

    protected $table = "almacenes_entradas_devoluciones_descripcion";

    protected $primaryKey = "id_entrada_devolucion_descripcion";

    public function buscar($datos)
    {

        $query = $this->leftJoin("almacenes_entradas_devoluciones as aed", "aed.id_entrada_devolucion", "=", "almacenes_entradas_devoluciones_descripcion.id_entrada_devolucion");
        $query->leftJoin("devoluciones as d", "d.id_devolucion", "=", "aed.id_devolucion");
        $query->leftJoin("productos as p", "p.id_producto", "=", "almacenes_entradas_devoluciones_descripcion.id_producto");

        $query->select(
            "almacenes_entradas_devoluciones_descripcion.*",
            \DB::raw("ifnull(d.id_venta,'') as id_venta")
        );

        if (!empty($datos["id_sucursal"])){

            $query->where("almacenes_entradas_devoluciones_descripcion.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["id_almacen"])){

            $query->where("almacenes_entradas_devoluciones_descripcion.id_almacen", $datos["id_almacen"]);

        }

        if (!empty($datos["codigo_producto"])){

            $query->where("p.codigo_producto", $datos["codigo_producto"]);

        }

        if (!empty($datos["fecha_inicio"])){

            $query->where("almacenes_entradas_devoluciones_descripcion.fecha",  ">=", $datos["fecha_inicio"]);

        }

        if (!empty($datos["fecha_final"])){

            $query->where("almacenes_entradas_devoluciones_descripcion.fecha",  "<=", $datos["fecha_final"]);

        }

        $query->where("almacenes_entradas_devoluciones_descripcion.aplicacion", "=", "entrada");

        return $query->get();

    }

}
