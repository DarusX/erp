<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VentasPromediosSucursalesEdicion extends Model
{
    protected $table = 'ventas_promedios_sucursales_ediciones';
    protected $fillable = ['venta_promedio_sucursal_id', 'sucursal_id', 'producto_id', 'porcentaje_anterior', 'porcentaje_nuevo', 'usuario_solicitud_id'];

    public function buscarPendiente($datos)
    {
        $query = $this->from('ventas_promedios_sucursales_ediciones AS vpse')
            ->select(
                'vpse.*',
                'u.nombre AS usuario_solicitud',
                'cs.nombre AS sucursal',
                'p.codigo_producto',
                'p.descripcion'
            )
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'vpse.usuario_solicitud_id')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'vpse.sucursal_id')
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'vpse.producto_id')
            ->where('venta_promedio_sucursal_id', $datos['venta_promedio_sucursal_id']);

        return $query->first();
    }

    public function buscarValidado($datos)
    {
        $query = $this->from('ventas_promedios_sucursales_ediciones AS vpse')
            ->select(
                'vpse.*',
                'u.nombre AS usuario_solicitud',
                'u2.nombre AS usuario_validacion',
                'cs.nombre AS sucursal',
                'p.codigo_producto',
                'p.descripcion'
            )
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'vpse.usuario_solicitud_id')
            ->leftJoin('usuarios AS u2', 'u2.id_usuario', '=', 'vpse.usuario_validacion_id')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'vpse.sucursal_id')
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'vpse.producto_id')
            ->where('venta_promedio_sucursal_id', $datos['venta_promedio_sucursal_id']);

        return $query->first();
    }

    public function ventaPromedioSucursal()
    {
        return $this->belongsTo(VentasPromediosSucursal::class, 'venta_promedio_sucursal_id');
    }
}