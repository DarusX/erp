<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlmacenesEntradasTransfDescripcion extends Model
{
    protected $table = 'almacenes_entradas_transferencias_descripcion';
    protected $primaryKey = "id_entrada_transferencia_descripcion";
    public $timestamps = false;

    public function buscarEntradas($datos){

        $query = $this->leftJoin("transferencias_ordenes as ot", "ot.id_transferencia_orden", "=", "almacenes_entradas_transferencias_descripcion.id_transferencia_orden");
        $query->leftJoin("transferencias_ordenes_descripcion as otd", "otd.id_transferencia_descripcion", "=", "almacenes_entradas_transferencias_descripcion.id_transferencia_descripcion");
        $query->leftJoin("transferencias as t", "t.id_transferencia", "=", "almacenes_entradas_transferencias_descripcion.id_transferencia");

        $query->select(
            \DB::raw("ifnull(sum(almacenes_entradas_transferencias_descripcion.cantidad),0) as cantidad")
        );

        if(!empty($datos["id_transferencia"])){

            $query->where("almacenes_entradas_transferencias_descripcion.id_transferencia", $datos["id_transferencia"]);

        }

        //dd($query->toSql());

        return$query->first();

    }

    public function buscar($datos)
    {

        $query = $this->leftJoin("almacenes_entradas_transferencias as aet", "aet.id_entrada_transferencia", "=", "almacenes_entradas_transferencias_descripcion.id_entrada_transferencia");
        $query->leftJoin("productos as p", "p.id_producto", "=", "almacenes_entradas_transferencias_descripcion.id_producto");
        $query->leftJoin("almacenes as ao", "ao.id_almacen", "=", "almacenes_entradas_transferencias_descripcion.almacen_origen");
        $query->leftJoin("almacenes as ad", "ad.id_almacen", "=", "almacenes_entradas_transferencias_descripcion.almacen_destino");

        $query->select(
            "almacenes_entradas_transferencias_descripcion.*",
            "aet.fecha",
            "ao.almacen as almacen_o",
            "ad.almacen as almacen_d"
        );

        if (!empty($datos["id_sucursal"])){

            $query->where("almacenes_entradas_transferencias_descripcion.sucursal_destino", $datos["id_sucursal"]);

        }

        if (!empty($datos["id_almacen"])){

            $query->where("almacenes_entradas_transferencias_descripcion.almacen_destino", $datos["id_almacen"]);

        }

        if (!empty($datos["codigo_producto"])){

            $query->where("p.codigo_producto", $datos["codigo_producto"]);

        }

        if (!empty($datos["fecha_inicio"])){

            $query->where("aet.fecha",  ">=", $datos["fecha_inicio"]);

        }

        if (!empty($datos["fecha_final"])){

            $query->where("aet.fecha",  "<=", $datos["fecha_final"]);

        }

        //dd($query->toSql());
        return $query->get();

    }

}
