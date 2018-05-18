<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FamiliasPorcentajesUtilidadesEdicion extends Model
{
    protected $table = 'familias_porcentajes_utilidades_ediciones';

    protected $fillable = [
        "producto_familia_porcentaje_utilidad_id",
        "tipo_venta_id",
        "tipo_precio_id",
        "porcentaje_anterior",
        "porcentaje_nuevo",
        "estado",
        "aplicado",
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
            'fpue.*',
            'u.nombre AS usuario_solicitud',
            'cs.nombre AS sucursal',
            //'vt.tipo AS precio',
            \DB::raw("ifnull(vtp.tipo,vt.tipo) as tipo_precio")
        )
            ->from('familias_porcentajes_utilidades_ediciones AS fpue')
            ->leftJoin('productos_familias_porcentaje_utilidad AS pfpu', 'pfpu.id', '=', 'fpue.producto_familia_porcentaje_utilidad_id')
            ->leftJoin('ventas_tipo_precio AS vtp', 'vtp.id', '=', 'fpue.tipo_precio_id')
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'fpue.usuario_solicitud_id')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'pfpu.sucursal_id')
            ->leftJoin('ventas_tipos AS vt', 'vt.id_tipo_venta', '=', 'pfpu.tipo_venta_id')
            ->where('pfpu.familia_id', $datos["familia_id"])
            ->where('fpue.estado', 'Pendiente');

        return $query->get();
    }

    public function buscarValidadas($datos)
    {
        $query = $this->select(
            'fpue.*',
            'u.nombre AS usuario_solicitud',
            'u2.nombre AS usuario_validacion',
            'cs.nombre AS sucursal',
            //'vt.tipo AS precio',
            \DB::raw("ifnull(vtp.tipo,vt.tipo) as tipo_precio")
        )
            ->from('familias_porcentajes_utilidades_ediciones AS fpue')
            ->leftJoin('productos_familias_porcentaje_utilidad AS pfpu', 'pfpu.id', '=', 'fpue.producto_familia_porcentaje_utilidad_id')
            ->leftJoin('ventas_tipo_precio AS vtp', 'vtp.id', '=', 'fpue.tipo_precio_id')
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'fpue.usuario_solicitud_id')
            ->leftJoin('usuarios AS u2', 'u2.id_usuario', '=', 'fpue.usuario_validacion_id')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'pfpu.sucursal_id')
            ->leftJoin('ventas_tipos AS vt', 'vt.id_tipo_venta', '=', 'pfpu.tipo_venta_id')
            ->where('pfpu.familia_id', $datos["familia_id"])
            ->where('fpue.estado', 'Validado');

        return $query->get();
    }

    public function productoFamiliaPorcentajeUtilidad()
    {
        return $this->belongsTo(Familia_porcentaje_utilidad::class, 'producto_familia_porcentaje_utilidad_id');
    }
}