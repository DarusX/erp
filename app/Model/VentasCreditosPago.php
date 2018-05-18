<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class VentasCreditosPago extends Model
{
    protected $table = 'ventas_credito_pagos';
    public $timestamps = false;

    public function buscar($parametros)
    {
        $query = $this->select();

        if (!empty($parametros['codigo_cliente'])) {
            $query = $query->where('vcp.cliente_codigo', $parametros['codigo_cliente']);
        }

        return $query->get();
    }

    public function saldoCliente(array $parametros)
    {
        $query = $this->from('ventas_credito_pagos AS vcp')
            ->select(
                \DB::raw('SUM(vcp.cantidad) AS monto_total'),
                \DB::raw('SUM( IFNULL( (SELECT SUM(vcpd.cantidad) from ventas_credito_pagos_descripcion as vcpd where vcpd.id_venta = vcp.id_venta) ,0) ) AS monto_abonado')
            );

        if (!empty($parametros['codigo_cliente'])) {
            $query = $query->where('vcp.cliente_codigo', $parametros['codigo_cliente']);
        }

        return $query->where('vcp.estatus', 'Pendiente')
            ->first();
    }

    public function creditosVencidos($cliente)
    {
        $query = $this->from('ventas_credito_pagos AS vcp')
            ->select(
                \DB::raw('COUNT(*) AS cantidad')
            )
            ->where('vcp.cliente_codigo', $cliente)
            ->where('vcp.estatus', 'Pendiente')
            ->where('fecha', '<=', Carbon::now()->toDateString());

        return $query->first();
    }
}