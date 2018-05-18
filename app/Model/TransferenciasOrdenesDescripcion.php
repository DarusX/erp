<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TransferenciasOrdenesDescripcion extends Model
{
    protected $table = "transferencias_ordenes_descripcion";

    protected $primarykey = "id_transferencia_descripcion";

    protected $fillable = [
        'id_transferencia_orden',
        'id_transferencia',
        'id_producto',
        'cantidad',
        'sucursal_origen',
        'almacen_origen',
        'sucursal_destino',
        'almacen_destino',
        'observaciones',
        'estatus',
        'id_empleado_finalizado',
        'fecha_finalizado'
    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin('almacenes as a_origen', 'a_origen.id_almacen', '=', 'transferencias_ordenes_descripcion.almacen_origen');
        $query->leftJoin('almacenes as a_destino', 'a_destino.id_almacen', '=', 'transferencias_ordenes_descripcion.almacen_destino');
        $query->leftJoin('cat_sucursales as s_origen', 's_origen.id_sucursal', '=', 'transferencias_ordenes_descripcion.sucursal_origen');
        $query->leftJoin('cat_sucursales as s_destino', 's_destino.id_sucursal', '=', 'transferencias_ordenes_descripcion.sucursal_destino');
        $query->leftJoin('productos as p', 'p.id_producto', '=', 'transferencias_ordenes_descripcion.id_producto');
        $query->leftJoin('almacenes_reservados_transferencias as art', 'art.id_transferencia', '=', 'transferencias_ordenes_descripcion.id_transferencia');

        $query->select(
            'transferencias_ordenes_descripcion.*',
            'a_origen.almacen as a_origen',
            'a_destino.almacen as a_destino',
            's_origen.nombre as s_origen',
            's_destino.nombre as s_destino',
            'p.codigo_producto',
            'p.descripcion',
            'p.peso',
            'art.cantidad as cantidad_reserva',
            \DB::raw('(SELECT IFNULL(SUM(almacenes_salidas_ordenes_transferencias_descripcion.cantidad),0) FROM almacenes_salidas_ordenes_transferencias_descripcion WHERE almacenes_salidas_ordenes_transferencias_descripcion.id_transferencia_descripcion = transferencias_ordenes_descripcion.id_transferencia_descripcion) AS salidas'),
            \DB::raw('(SELECT IFNULL(SUM(almacenes_entradas_transferencias_descripcion.cantidad),0) FROM almacenes_entradas_transferencias_descripcion WHERE almacenes_entradas_transferencias_descripcion.id_transferencia_descripcion = transferencias_ordenes_descripcion.id_transferencia_descripcion) AS entradas')
        );

        if (!empty($datos['id_transferencia_orden'])) {
            $query->where('transferencias_ordenes_descripcion.id_transferencia_orden', $datos['id_transferencia_orden']);
        }
        if (!empty($datos['id_transferencia_descripcion'])) {
            $query->where('transferencias_ordenes_descripcion.id_transferencia_descripcion', $datos['id_transferencia_descripcion']);
        }
        if (!empty($datos["id_sucursal"])){
            $query->where("transferencias_ordenes_descripcion.sucursal_origen", $datos["id_sucursal"]);
        }
        if (!empty($datos["id_almacen"])){
            $query->where("transferencias_ordenes_descripcion.almacen_origen", $datos["id_almacen"]);
        }
        if (!empty($datos["id_producto"])){
            $query->where("transferencias_ordenes_descripcion.id_producto", $datos["id_producto"]);
        }
        if (!empty($datos["estatus"])){
            $query->where("transferencias_ordenes_descripcion.estatus", $datos["estatus"]);
        }

        //dd($query->toSql());

        return $query->get();
    }

    public function buscarPDF($datos)
    {
        $query = $this->leftJoin('almacenes as a_origen', 'a_origen.id_almacen', '=', 'transferencias_ordenes_descripcion.almacen_origen');
        $query->leftJoin('almacenes as a_destino', 'a_destino.id_almacen', '=', 'transferencias_ordenes_descripcion.almacen_destino');
        $query->leftJoin('productos as p', 'p.id_producto', '=', 'transferencias_ordenes_descripcion.id_producto');
        $query->leftJoin('productos_unidades_medida as um', 'um.id_unidad_medida', '=', 'p.unidad_compra');

        $query->select(
            'transferencias_ordenes_descripcion.*',
            'a_origen.almacen as a_origen',
            'a_destino.almacen as a_destino',
            'p.codigo_producto',
            'p.descripcion',
            'p.peso',
            'um.unidad_medida',
            \DB::raw('(SELECT SUM(almacenes_salidas_ordenes_transferencias_descripcion.cantidad) FROM almacenes_salidas_ordenes_transferencias_descripcion WHERE almacenes_salidas_ordenes_transferencias_descripcion.id_transferencia_descripcion = transferencias_ordenes_descripcion.id_transferencia_descripcion) AS salidas'),
            \DB::raw('(SELECT SUM(almacenes_entradas_transferencias_descripcion.cantidad) FROM almacenes_entradas_transferencias_descripcion WHERE almacenes_entradas_transferencias_descripcion.id_transferencia_descripcion = transferencias_ordenes_descripcion.id_transferencia_descripcion) AS entradas')
        );

        if (!empty($datos['id_transferencia_orden'])) {
            $query->where('transferencias_ordenes_descripcion.id_transferencia_orden', $datos['id_transferencia_orden']);
        }

        //dd($query->toSql());

        return $query->get();
    }

    public function obtenerTransferenciasOrdenes($id_producto, $id_almacen)
    {

        $query = $this->from("transferencias_ordenes_descripcion as tod");

        $query->select(
            \DB::raw("ifnull(SUM(IFNULL((tod.cantidad),0) - IFNULL((select SUM(e.cantidad) from almacenes_entradas_transferencias_descripcion as e where tod.id_transferencia_descripcion = e.id_transferencia_descripcion),0)),0) as cantidad")
        );

        $query->whereNotIn("tod.estatus", ["finalizada", "cancelada"]);
        $query->where("tod.id_producto", $id_producto);
        $query->where("tod.almacen_destino", $id_almacen);

        return $query->first();

    }

}
