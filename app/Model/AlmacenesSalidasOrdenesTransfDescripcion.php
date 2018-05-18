<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlmacenesSalidasOrdenesTransfDescripcion extends Model
{
    protected $table = 'almacenes_salidas_ordenes_transferencias_descripcion';

    protected $primarykey = "id_orden_salida_transferencia_descripcion";

    public $timestamps = false;

    public function buscarSalidas($datos){

        $query = $this->leftJoin("almacenes_salidas_ordenes_transferencias as asot", "asot.id_salida_orden_transferencia", "=", "almacenes_salidas_ordenes_transferencias_descripcion.id_salida_orden_transferencia");
        $query->leftJoin("transferencias_ordenes as ot", "ot.id_transferencia_orden", "=", "almacenes_salidas_ordenes_transferencias_descripcion.id_transferencia_orden");
        $query->leftJoin("transferencias_ordenes_descripcion as otd", "otd.id_transferencia_descripcion", "=", "almacenes_salidas_ordenes_transferencias_descripcion.id_transferencia_descripcion");
        $query->leftJoin("transferencias as t", "t.id_transferencia", "=", "almacenes_salidas_ordenes_transferencias_descripcion.id_transferencia");

        $query->select(
            \DB::raw("ifnull(sum(almacenes_salidas_ordenes_transferencias_descripcion.cantidad),0) as cantidad")
        );

        if(!empty($datos["id_transferencia"])){

            $query->where("almacenes_salidas_ordenes_transferencias_descripcion.id_transferencia", $datos["id_transferencia"]);

        }

        //dd($query->toSql());

        return$query->first();

    }

    public function buscar($datos)
    {

        $query = $this->leftJoin("almacenes_salidas_ordenes_transferencias as asot", "asot.id_salida_orden_transferencia", "=", "almacenes_salidas_ordenes_transferencias_descripcion.id_salida_orden_transferencia");
        $query->leftJoin("productos as p", "p.id_producto", "=", "almacenes_salidas_ordenes_transferencias_descripcion.id_producto");

        $query->select(
            "almacenes_salidas_ordenes_transferencias_descripcion.*",
            "asot.fecha"
        );

        if (!empty($datos["id_sucursal"])){

            $query->where("almacenes_salidas_ordenes_transferencias_descripcion.sucursal_origen", $datos["id_sucursal"]);

        }

        if (!empty($datos["id_almacen"])){

            $query->where("almacenes_salidas_ordenes_transferencias_descripcion.almacen_origen", $datos["id_almacen"]);

        }

        if (!empty($datos["codigo_producto"])){

            $query->where("p.codigo_producto", $datos["codigo_producto"]);

        }

        if (!empty($datos["fecha_inicio"])){

            $query->where("asot.fecha",  ">=", $datos["fecha_inicio"]);

        }

        if (!empty($datos["fecha_final"])){

            $query->where("asot.fecha",  "<=", $datos["fecha_final"]);

        }

        return $query->get();

    }

}
