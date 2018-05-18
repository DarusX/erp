<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class InmobiliariaVentasAbonos extends Model
{
    protected $table = 'ventas_abonos_inmobiliaria';
    protected $fillable = ['venta_id', 'fecha_hora_pago', 'monto', 'concepto', 'tipo', 'factura', 'pago', 'usuario_id'];

    public function buscar($datos)
    {
        $query = $this->select(
            'ventas_abonos_inmobiliaria.*',
            'ii.nombre AS inmueble',
            'c.nombre AS cliente'
        )
            ->leftJoin('ventas_inmobiliaria AS v', 'v.id', '=', 'ventas_abonos_inmobiliaria.venta_id')
            ->leftJoin('inmuebles_inmobiliaria AS ii', 'ii.id', '=', 'v.inmueble_id')
            ->leftJoin('catalogos_clientes_inmobiliaria AS c', 'c.id', '=', 'v.cliente_id');

        if (!empty($datos['venta_id'])) {
            $query = $query->where('venta_id', $datos['venta_id']);
        }
        if (!empty($datos['inmueble_id'])) {
            $query = $query->where('ii.id', $datos['inmueble_id']);
        }
        if (!empty($datos['factura'])) {
            $query = $query->where('factura', $datos['factura']);
        }
        if (!empty($datos['tipo'])) {
            $query = $query->where('ventas_abonos_inmobiliaria.tipo', $datos['tipo']);
        }
        if (!empty($datos['estado'])) {
            $query = $query->where('ventas_abonos_inmobiliaria.estado', $datos['estado']);
        }
        return $query->get();
    }

    public function setComprobanteAttribute($value)
    {
        if ($value) {
            $carpeta = 'inmobiliaria/ventas/';
            $ext = $value->getClientOriginalExtension();
            $nombre = md5(time()) . "." . $ext;
            $value->move($carpeta, $nombre);
            $this->attributes['comprobante'] = $carpeta . $nombre;
        }
    }

    public function venta()
    {
        return $this->belongsTo(InmobiliariaVentas::class, 'venta_id');
    }

    public function setFechaHoraPagoAttribute($value)
    {
        $this->attributes['fecha_hora_pago'] = $value == "" ? Carbon::now()->toDateTimeString() : $value;
    }

}