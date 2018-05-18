<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductosPorcentajesUtilidadesEdicion extends Model
{
    protected $table = 'productos_porcentajes_utilidades_ediciones';

    protected $fillable = [
        "producto_porcentaje_utilidad_id",
        "tipo_venta_id",
        "tipo_precio_id",
        "porcentaje_anterior",
        "porcentaje_nuevo",
        "estado",
        "validacion",
        "usuario_validacion_id",
        "autorizacion",
        "usuario_autorizacion_id",
        "rechazo",
        "usuario_rechazo_id",
        "usuario_solicitud_id"
    ];

    public function buscarPendientes($datos)
    {
        $query = $this->select(
            'ppue.*',
            'u.nombre AS usuario_solicitud',
            'cs.nombre AS sucursal',
            //'vt.tipo AS precio',
            \DB::raw("ifnull(vtp.tipo,vt.tipo) as tipo_precio")
        )
            ->from('productos_porcentajes_utilidades_ediciones AS ppue')
            ->leftJoin('productos_porcentaje_utilidad AS ppu', 'ppu.id', '=', 'ppue.producto_porcentaje_utilidad_id')
            ->leftJoin('ventas_tipo_precio AS vtp', 'vtp.id', '=', 'ppu.tipo_precio_id')
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'ppue.usuario_solicitud_id')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'ppu.sucursal_id')
            ->leftJoin('ventas_tipos AS vt', 'vt.id_tipo_venta', '=', 'ppu.tipo_venta_id')
            ->where('ppu.producto_id', $datos["producto_id"])
            ->where('ppue.estado', 'Pendiente');

        return $query->get();
    }

    public function buscarValidadas($datos)
    {
        $query = $this->select(
            'ppue.*',
            'u.nombre AS usuario_solicitud',
            'u2.nombre AS usuario_validacion',
            'cs.nombre AS sucursal',
            //'vt.tipo AS precio',
            \DB::raw("ifnull(vtp.tipo,vt.tipo) as tipo_precio")
        )
            ->from('productos_porcentajes_utilidades_ediciones AS ppue')
            ->leftJoin('productos_porcentaje_utilidad AS ppu', 'ppu.id', '=', 'ppue.producto_porcentaje_utilidad_id')
            ->leftJoin('ventas_tipo_precio AS vtp', 'vtp.id', '=', 'ppu.tipo_precio_id')
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'ppue.usuario_solicitud_id')
            ->leftJoin('usuarios AS u2', 'u2.id_usuario', '=', 'ppue.usuario_validacion_id')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'ppu.sucursal_id')
            ->leftJoin('ventas_tipos AS vt', 'vt.id_tipo_venta', '=', 'ppu.tipo_venta_id')
            ->where('ppu.producto_id', $datos["producto_id"])
            ->where('ppue.estado', 'Validado');

        return $query->get();
    }

    public function productoPorcentajeUtilidad()
    {
        return $this->belongsTo(producto_utilidad::class, 'producto_porcentaje_utilidad_id');
    }
}