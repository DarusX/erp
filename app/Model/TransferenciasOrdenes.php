<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TransferenciasOrdenes extends Model
{
    protected $table = 'transferencias_ordenes';

    protected $primarykey = 'id_transferencia_orden';

    protected $fillable = [
        'sucursal_origen',
        'sucursal_destino',
        'id_usuario',
        'fecha_orden',
        'usuario_autoriza_id',
        'fecha_autorizacion',
        'estatus',
        'tipo',
        'orden_directa',
        'observaciones',
        'clasificacion_transferencia',
        'venta_id'
    ];

    public function buscar($datos){
        $query = $this->leftJoin('cat_sucursales as s_origen', 's_origen.id_sucursal', '=', 'transferencias_ordenes.sucursal_origen');
        $query->leftJoin('cat_sucursales as s_destino', 's_destino.id_sucursal', '=', 'transferencias_ordenes.sucursal_destino');
        $query->leftJoin('usuarios as u', 'u.id_usuario', '=', 'transferencias_ordenes.id_usuario');

        $query->select(
            'transferencias_ordenes.*',
            's_origen.nombre as s_origen',
            's_destino.nombre as s_destino',
            'u.nombre'
        );

        if(!empty($datos['id_orden'])){
            $query->where('transferencias_ordenes.id_transferencia_orden', $datos['id_orden']);
            if(!empty($datos['first'])){
                return $query->first();
            }
        }
        if(!empty($datos['s_origen'])){
            $query->where('transferencias_ordenes.sucursal_origen', $datos['s_origen']);
        }
        if(!empty($datos['s_destino'])){
            $query->where('transferencias_ordenes.sucursal_destino', $datos['s_destino']);
        }
        if(!empty($datos['estatus'])){
            $query->where('transferencias_ordenes.estatus', $datos['estatus']);
        }
        if(!empty($datos['id_usuario'])){
            $query->where('transferencias_ordenes.id_usuario', $datos['id_usuario']);
        }
        if(!empty($datos['tipo'])){
            $query->where('transferencias_ordenes.tipo', $datos['tipo']);
        }
        if (!empty($datos['fecha_ini'])) {
            $query->where('fecha_orden', '>=', $datos['fecha_ini']);
        }
        if (!empty($datos['fecha_fin'])) {
            $query->where('fecha_orden', '<=', $datos['fecha_fin']);
        }

        //dd($query->toSql());
        return $query->get();
    }

    public function buscar_salidas($datos){

        $query = $this->leftJoin('almacenes_salidas_ordenes_transferencias_descripcion as salidas', 'salidas.id_transferencia_orden', '=', 'transferencias_ordenes.id_transferencia_orden');
        $query->leftJoin('productos as p', 'p.id_producto', '=', 'salidas.id_producto');
        $query->leftJoin('almacenes_salidas_ordenes_transferencias as salidas_t', 'salidas_t.id_salida_orden_transferencia', '=', 'salidas.id_salida_orden_transferencia');

        $query->select(
            \DB::raw("ifnull(p.codigo_producto,'') as codigo_producto"),
            \DB::raw("ifnull(p.descripcion,'') as descripcion"),
            \DB::raw("ifnull(salidas_t.fecha,'') as fecha"),
            \DB::raw("ifnull(salidas.cantidad,'') as cantidad")
        );

        if(!empty($datos['id_transferencia_orden'])){
            $query->where('transferencias_ordenes.id_transferencia_orden', $datos['id_transferencia_orden']);
        }

        //dd($query->toSql());

        return $query->get();

    }

    public function buscarPDF($datos){
        $query = $this->leftJoin('cat_sucursales as s_origen', 's_origen.id_sucursal', '=', 'transferencias_ordenes.sucursal_origen');
        $query->leftJoin('cat_sucursales as s_destino', 's_destino.id_sucursal', '=', 'transferencias_ordenes.sucursal_destino');
        $query->leftJoin('usuarios as u', 'u.id_usuario', '=', 'transferencias_ordenes.id_usuario');

        $query->select(
            'transferencias_ordenes.*',
            's_origen.nombre as s_origen',
            's_destino.nombre as s_destino',
            'u.nombre',
            \DB::raw("obtenerPresolicitudes(transferencias_ordenes.id_transferencia_orden) as presolicitudes")
        );

        if(!empty($datos['id_orden'])){
            $query->where('transferencias_ordenes.id_transferencia_orden', $datos['id_orden']);
        }
        if(!empty($datos['s_origen'])){
            $query->where('transferencias_ordenes.sucursal_origen', $datos['s_origen']);
        }
        if(!empty($datos['s_destino'])){
            $query->where('transferencias_ordenes.sucursal_destino', $datos['s_destino']);
        }
        if(!empty($datos['estatus'])){
            $query->where('transferencias_ordenes.estatus', $datos['estatus']);
        }
        if(!empty($datos['id_usuario'])){
            $query->where('transferencias_ordenes.id_usuario', $datos['id_usuario']);
        }
        if(!empty($datos['tipo'])){
            $query->where('transferencias_ordenes.tipo', $datos['tipo']);
        }

        //dd($query->toSql());
        return $query->first();
    }
}
