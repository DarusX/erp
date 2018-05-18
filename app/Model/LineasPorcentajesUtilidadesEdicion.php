<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LineasPorcentajesUtilidadesEdicion extends Model
{
    protected $table = 'lineas_porcentajes_utilidades_ediciones';

    protected $fillable = [
        "producto_linea_porcentaje_utilidad_id",
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
            'lpue.*',
            'u.nombre AS usuario_solicitud',
            'cs.nombre AS sucursal',
            //'vt.tipo AS precio',
            \DB::raw("ifnull(vtp.tipo,vt.tipo) as tipo_precio")
        )
            ->from('lineas_porcentajes_utilidades_ediciones AS lpue')
            ->leftJoin('productos_lineas_porcentaje_utilidad AS plpu', 'plpu.id', '=', 'lpue.producto_linea_porcentaje_utilidad_id')
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'lpue.usuario_solicitud_id')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'plpu.sucursal_id')
            ->leftJoin('ventas_tipos AS vt', 'vt.id_tipo_venta', '=', 'plpu.tipo_venta_id')
            ->leftJoin('ventas_tipo_precio as vtp', 'vtp.id', '=', 'plpu.tipo_precio_id')
            ->where('plpu.linea_id', $datos["linea_id"])
            ->where('lpue.estado', 'Pendiente');

        return $query->get();
    }


    public function buscarValidadas($datos)
    {
        $query = $this->select(
            'lpue.*',
            'u.nombre AS usuario_solicitud',
            'u2.nombre AS usuario_validacion',
            'cs.nombre AS sucursal',
            //'vt.tipo AS precio',
            \DB::raw("ifnull(vtp.tipo,vt.tipo) as tipo_precio")
        )
            ->from('lineas_porcentajes_utilidades_ediciones AS lpue')
            ->leftJoin('productos_lineas_porcentaje_utilidad AS plpu', 'plpu.id', '=', 'lpue.producto_linea_porcentaje_utilidad_id')
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'lpue.usuario_solicitud_id')
            ->leftJoin('usuarios AS u2', 'u2.id_usuario', '=', 'lpue.usuario_validacion_id')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'plpu.sucursal_id')
            ->leftJoin('ventas_tipos AS vt', 'vt.id_tipo_venta', '=', 'plpu.tipo_venta_id')
            ->leftJoin('ventas_tipo_precio as vtp', 'vtp.id', '=', 'plpu.tipo_precio_id')
            ->where('plpu.linea_id', $datos["linea_id"])
            ->where('lpue.estado', 'Validado');

        return $query->get();
    }

    public function productoLineaPorcentajeUtilidad()
    {
        return $this->belongsTo(Linea_utilidad::class, 'producto_linea_porcentaje_utilidad_id');
    }
}