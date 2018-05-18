<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class presolicitudTransferenciaDetalle extends Model
{
    //
    protected $table = "presolicitud_transferencia_detalle";
    protected $fillable = [
        'presolicitud_id',
        'sucursal_origen_id',
        'almacen_origen_id',
        'sucursal_destino_id',
        'almacen_destino_id',
        'producto_id',
        'cantidad'

    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("cat_sucursales as sd", "sd.id_sucursal", "=", "presolicitud_transferencia_detalle.sucursal_destino_id");
        $query->leftJoin("cat_sucursales as so", "so.id_sucursal", "=", "presolicitud_transferencia_detalle.sucursal_origen_id");
        $query->leftJoin("presolicitud_transferencia as pt", "pt.id", "=", "presolicitud_transferencia_detalle.presolicitud_id");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "presolicitud_transferencia_detalle.almacen_origen_id");
        $query->leftJoin("almacenes as a2", "a2.id_almacen", "=", "presolicitud_transferencia_detalle.almacen_destino_id");
        $query->leftJoin("productos as p", "p.id_producto", "=", "presolicitud_transferencia_detalle.producto_id");
        $query->leftJoin("almacenes_existencias as ax", function ($join) {
            $join->on("ax.id_producto", "=", "presolicitud_transferencia_detalle.producto_id")
                ->on("ax.id_almacen", "=", "presolicitud_transferencia_detalle.almacen_origen_id");
        });

        if (!empty($datos["presolicitud_id"])) {
            $query->where("pt.id", "=", $datos["presolicitud_id"]);
        }


        $select = [
            "presolicitud_transferencia_detalle.*",
            "a.almacen as almacen_origen",
            "a2.almacen as almacen_destino",
            "sd.nombre as sucursal_destino",
            "so.nombre as sucursal_origen",
            "p.codigo_producto",
            "p.descripcion",
            "p.unidad_venta as unidad_medida",
            "ax.existencia"

        ];

        $query->select($select);

        if (!empty($datos["first"]))
            return $query->first();

        return $query->get();

    }
}
