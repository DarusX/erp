<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlmacenesReservadosTransferencias extends Model
{
    protected $table = "almacenes_reservados_transferencias";

    protected $primarykey = "id_reservado_transferencia";

    protected $fillable = [
        'almacen_origen',
        'sucursal_origen',
        'almacen_destino',
        'sucursal_destino',
        'id_producto',
        'id_unidad_medida',
        'id_transferencia',
        'id_usuario',
        'cantidad',
        'fecha_reserva',
        'estatus'
    ];

    public function buscarTransferencias($datos)
    {

        $query = $this->leftJoin("transferencias as t", "t.id_transferencia", "=", "almacenes_reservados_transferencias.id_transferencia");
        $query->leftJoin("transferencias_ordenes_descripcion as tod", "tod.id_transferencia", "=", "t.id_transferencia");
        $query->leftJoin("transferencias_ordenes as ot", "ot.id_transferencia_orden", "=", "tod.id_transferencia_orden");

        $query->select(
            "ot.id_transferencia_orden",
            "almacenes_reservados_transferencias.cantidad"
        );

        //$query->where("almacenes_reservados_transferencias.sucursal_origen", $datos["id_sucursal"]);
        $query->where("almacenes_reservados_transferencias.almacen_origen", $datos["id_almacen"]);
        $query->where("almacenes_reservados_transferencias.id_producto", $datos["id_producto"]);
        $query->where("tod.cantidad", ">", "0");

        //dd($query->toSql());
        return $query->get();

    }

}
