<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlmacenesEntradasOrdenesDescripcion extends Model
{
    protected $table = "almacenes_entradas_ordenes_descripcion";
    protected $primarykey = "id_entrada_descripcion";

    public $timestamps = false;

    public function buscar($datos)
    {

        $query = $this->leftJoin("almacenes_entradas_ordenes as aeo", "aeo.id_entrada_orden", "=", "almacenes_entradas_ordenes_descripcion.id_entrada_orden");
        $query->leftJoin("productos as p", "p.id_producto", "=", "almacenes_entradas_ordenes_descripcion.id_producto");
        $query->leftJoin("cat_proveedores as pro", "pro.id_proveedor", "=", "almacenes_entradas_ordenes_descripcion.id_proveedor");

        $query->select(
            "almacenes_entradas_ordenes_descripcion.*",
            "aeo.factura",
            "aeo.fecha_entrada",
            "pro.nombre",
            \DB::raw("ifnull(aeo.factura, '') as factura")
        );

        if (!empty($datos["id_sucursal"])) {

            $query->where("almacenes_entradas_ordenes_descripcion.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["id_almacen"])) {

            $query->where("almacenes_entradas_ordenes_descripcion.id_almacen", $datos["id_almacen"]);

        }

        if (!empty($datos["codigo_producto"])) {

            $query->where("p.codigo_producto", $datos["codigo_producto"]);

        }

        if (!empty($datos["fecha_inicio"])) {

            $query->where("aeo.fecha_entrada", ">=", $datos["fecha_inicio"]);

        }

        if (!empty($datos["fecha_final"])) {

            $query->where("aeo.fecha_entrada", "<=", $datos["fecha_final"]);

        }

        if (!empty($datos["id_orden_descripcion"])) {

            $query->where("almacenes_entradas_ordenes_descripcion.id_orden_descripcion", $datos["id_orden_descripcion"]);

        }

        return $query->get();

    }

    public function ocIngresadas($parametros)
    {
        $query = $this->from('almacenes_entradas_ordenes_descripcion AS aeod')
            ->select(
                \DB::raw('SUM(aeod.cantidad * cod.precio) AS monto'),
                'aeo.fecha_entrada',
                'co.*'
            )
            ->leftJoin('almacenes_entradas_ordenes AS aeo', 'aeo.id_entrada_orden', '=', 'aeod.id_entrada_orden')
            ->leftJoin('compras_ordenes_descripcion AS cod', 'cod.id_orden_descripcion', '=', 'aeod.id_orden_descripcion')
            ->leftJoin('compras_ordenes AS co', 'co.id_orden', '=', 'cod.id_orden')
            ->whereBetween(\DB::raw('DATE(aeo.fecha_entrada)'), [$parametros['fecha_inicial'], $parametros['fecha_final']])
            //->whereNotBetween(\DB::raw('DATE(co.created_at)'), [$parametros['fecha_inicial'], $parametros['fecha_final']])
            ->where('co.concepto_emision', 'compra')
            ->groupBy('aeod.id_entrada_descripcion');

        if (!empty($parametros['anticipos'])) {
            if ($parametros['anticipos'] == "si") {
                $query = $query->where('co.tipo_compra', 'anticipado');
            } else {
                $query = $query->where('co.tipo_compra', '!=', 'anticipado');
            }
        }

        if (!empty($parametros['sucursal_id'])) {
            $query = $query->where('co.id_sucursal', $parametros['sucursal_id']);
        }

        return $query->get();
    }

}