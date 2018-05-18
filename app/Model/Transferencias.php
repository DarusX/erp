<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Transferencias extends Model
{
    protected $table = "transferencias";

    protected $primarykey = "id_transferencia";

    protected $fillable = [
        'id_producto',
        'cantidad',
        'sucursal_origen',
        'almacen_origen',
        'sucursal_destino',
        'almacen_destino',
        'id_unidad_medida',
        'id_usuario',
        'fecha_transferencia',
        'observaciones',
        'estatus'
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("productos as p", "p.id_producto", "=", "transferencias.id_producto");
        $query->leftJoin("cat_sucursales as s_origen", "s_origen.id_sucursal", "=", "transferencias.sucursal_origen");
        $query->leftJoin("cat_sucursales as s_destino", "s_destino.id_sucursal", "=", "transferencias.sucursal_destino");
        $query->leftJoin("almacenes as a_origen", "a_origen.id_almacen", "=", "transferencias.almacen_origen");
        $query->leftJoin("almacenes as a_destino", "a_destino.id_almacen", "=", "transferencias.almacen_destino");
        $query->leftJoin("productos_unidades_medida as um", "um.id_unidad_medida", "=", "transferencias.id_unidad_medida");

        $query->select(
            "transferencias.*",
            "s_origen.nombre as s_origen",
            "a_origen.almacen as a_origen",
            "s_destino.nombre as s_destino",
            "a_destino.almacen as a_destino",
            "um.unidad_medida as unidad_medida",
            "p.codigo_producto",
            "p.descripcion",
            "p.peso"
        );

        if(!empty($datos["id_transferencia"])){
            $query->where("id_transferencia", $datos["id_transferencia"]);
            if(!empty($datos["first"])){
                return $query->first();
            }
        }
        if(!empty($datos["sucursal_origen"])){
            $query->where("transferencias.sucursal_origen", $datos["sucursal_origen"]);
        }
        if(!empty($datos["sucursal_destino"])){
            $query->where("transferencias.sucursal_destino", $datos["sucursal_destino"]);
        }
        if(!empty($datos["almacen_origen"])){
            $query->where("transferencias.almacen_origen", $datos["almacen_origen"]);
        }
        if(!empty($datos["almacen_destino"])){
            $query->where("transferencias.almacen_destino", $datos["almacen_destino"]);
        }
        if(!empty($datos["id_producto"])){
            $query->where("transferencias.id_producto", $datos["id_producto"]);
        }
        if(!empty($datos["estatus"])){
            $query->where("transferencias.estatus", $datos["estatus"]);
        }

       //d($query->toSql());

        return $query->get();

    }

    public function obtenerTransferencias($id_producto, $id_almacen)
    {

        $query = $this->from("transferencias as t");

        $query->select(
            \DB::raw("ifnull(SUM(t.cantidad),0) as transferencias"),
            "t.almacen_destino",
            "t.id_producto"
        );

        $query->where("t.id_producto", $id_producto);
        $query->where("t.almacen_destino", $id_almacen);
        $query->whereIn("t.estatus", ["ps"]);

        return $query->first();

    }

}
