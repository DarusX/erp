<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VentasPromediosSucursal extends Model
{
    protected $table = 'ventas_promedios_sucursales';
    protected $fillable = ['sucursal_id', 'producto_id', 'porcentaje', 'promedio', 'promedio_porcentaje', 'tendencia'];

    public function buscar($datos)
    {
        $query = $this->from('ventas_promedios_sucursales AS vps')
            ->select(
                'vps.*',
                'p.codigo_producto',
                'cs.nombre AS sucursal',
                \DB::raw('(SELECT COUNT(*) FROM ventas_promedios_sucursales_ediciones WHERE estado = "Pendiente" AND venta_promedio_sucursal_id = vps.id) AS ediciones_porcentajes_pendientes'),
                \DB::raw('(SELECT COUNT(*) FROM ventas_promedios_sucursales_ediciones WHERE estado = "Validado" AND venta_promedio_sucursal_id = vps.id) AS ediciones_porcentajes_validadas')
            )
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'vps.producto_id')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'vps.sucursal_id');

        if (!empty($datos['familia_id'])) {
            $query = $query->where('p.id_familia', $datos['familia_id']);
        }

        if (!empty($datos['linea_id'])) {
            $query = $query->where('p.id_linea', $datos['linea_id']);
        }

        if (!empty($datos['sucursal_id'])) {
            $query = $query->where('vps.sucursal_id', $datos['sucursal_id']);
        }

        if (!empty($datos['producto_id'])) {
            $query = $query->where('vps.producto_id', $datos['producto_id']);
        }

        return $query->get();
    }
}