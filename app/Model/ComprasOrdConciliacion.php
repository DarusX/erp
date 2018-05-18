<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ComprasOrdConciliacion extends Model
{

    protected $table = "compras_ordenes_conciliacion";

    protected $fillable = [
        "id_orden",
        "id_descripcion_anterior",
        "id_orden_descripcion",
        "id_proveedor",
        "id_sucursal",
        "id_almacen",
        "id_producto",
        "cantidad_anterior",
        "cantidad",
        "precio",
        "id_nuevo_almacen",
        "tipo_partida",
        "id_empleado_concilia",
        "fecha_conciliacion",
        "id_orden_origen"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("compras_ordenes as c", "c.id_orden", "=", "compras_ordenes_conciliacion.id_orden");
        $query->leftJoin("compras_ordenes_descripcion as cd", "cd.id_orden_descripcion", "=", "compras_ordenes_conciliacion.id_orden_descripcion");
        $query->leftJoin("cat_proveedores as prov", "prov.id_proveedor", "=", "compras_ordenes_conciliacion.id_proveedor");
        $query->leftJoin("productos as p", "p.id_producto", "=", "compras_ordenes_conciliacion.id_producto");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "compras_ordenes_conciliacion.id_sucursal");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "compras_ordenes_conciliacion.id_almacen");
        $query->leftJoin("almacenes as na", "na.id_almacen", "=", "compras_ordenes_conciliacion.id_nuevo_almacen");
        $query->leftJoin("cat_sucursales as ns", "ns.id_sucursal", "=", "a.id_sucursal");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "compras_ordenes_conciliacion.id_empleado_concilia");
        $query->leftJoin("compras_ordenes as co", "co.id_orden", "=", "compras_ordenes_conciliacion.id_orden_origen");

        $query->select(
            "compras_ordenes_conciliacion.*",
            \DB::raw("ifnull(compras_ordenes_conciliacion.id_orden_origen,'S/R') as id_orden_origen"),
            "p.codigo_producto",
            "p.descripcion",
            "s.nombre as sucursal_actual",
            "ns.nombre as sucursal_anterior",
            "a.almacen as almacen_anterior",
            "na.almacen as almacen_actual",
            \DB::raw("ifnull(compras_ordenes_conciliacion.fecha_conciliacion,'S/R') as fecha_conciliacion"),
            \DB::raw("ifnull(concat(e.nombre, ' ', e.apaterno, ' ', e.amaterno),'S/R') as nombre_empleado"),
            \DB::raw("ifnull(compras_ordenes_conciliacion.id_descripcion_anterior, 'S/R') as id_descripcion_anterior"),
            \DB::raw("ifnull(compras_ordenes_conciliacion.cantidad_anterior, 'S/R') as cantidad_anterior")
        );

        if (!empty($datos["id_orden"])){

            $query->where("compras_ordenes_conciliacion.id_orden", $datos["id_orden"]);

        }

        //dd($query->toSql());
        return $query->get();

    }

}
