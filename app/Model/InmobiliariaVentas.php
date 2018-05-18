<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaVentas extends Model
{
    protected $table = 'ventas_inmobiliaria';

    public function buscar($datos)
    {
        $query = $this;

        if (!empty($datos['venta_id'])) {
            $query = $query->where('id', $datos['venta_id']);
        }

        return $query->get();
    }

    public function buscarEstados($datos)
    {
        $query = $this->select(
            'ventas_inmobiliaria.id',
            'ventas_inmobiliaria.created_at',
            'cci.nombre AS cliente',
            'ii.nombre AS inmueble',
            'cci2.nombre AS credito',
            'ceii.nombre AS estado',
            \DB::raw('DATEDIFF(fecha_hora_limite, NOW()) as diferencia'),
            \DB::raw('IFNULL(IF(vpi.estado = "Pendiente", "Si", "No"), "No") AS pendiente'),
            "u.nombre as vendedor"
        )
            ->leftJoin('inmuebles_inmobiliaria AS ii', 'ii.id', '=', 'ventas_inmobiliaria.inmueble_id')
            ->leftJoin('catalogos_clientes_inmobiliaria AS cci', 'cci.id', '=', 'ventas_inmobiliaria.cliente_id')
            ->leftJoin('catalogos_creditos_inmobiliaria AS cci2', 'cci2.id', '=', 'cci.credito_id')
            ->leftJoin('catalogos_estados_inmuebles_inmobiliaria AS ceii', 'ceii.id', '=', 'ii.estado_id')
            ->leftJoin('ventas_pendientes_inmobiliaria AS vpi', function ($join) {
                $join->on('vpi.venta_id', '=', 'ventas_inmobiliaria.id')
                    ->where('vpi.estado', '=', 'Pendiente');
            })
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'ventas_inmobiliaria.usuario_id');

        if (!empty($datos['venta_id'])) {
            $query = $query->where('id', $datos['venta_id']);
        }

        if (!empty($datos['proyecto_id'])) {
            $query = $query->where('ii.proyecto_id', $datos['proyecto_id']);
        }

        $query = $query
            ->whereBetween('estado_id', [2, 5])
            //->where('vpi.estado', 'Pendiente')
            ->where('ventas_inmobiliaria.estado', 'En Proceso');

        return $query->get();
    }

    public function notificacionAvaluo()
    {
        return $this->hasOne(InmobiliariaVentasNotificacionesAvaluo::class, 'venta_id');
    }

    public function notificacionEntrega()
    {
        return $this->hasOne(InmobiliariaVentasNotificacionesEntrega::class, 'venta_id');
    }

    public function inmueble()
    {
        return $this->belongsTo(InmobiliariaInmueble::class, 'inmueble_id');
    }

    public function cliente()
    {
        return $this->belongsTo(InmobiliariaCatalogosCliente::class, 'cliente_id');
    }

    public function abonos()
    {
        return $this->hasMany(InmobiliariaVentasAbonos::class, 'venta_id');
    }

    public function pendientes()
    {
        return $this->hasMany(InmobiliariaVentasPendientes::class, 'venta_id');
    }

    public function documentos()
    {
        return $this->hasMany(InmobiliariaVentasDocumentos::class, 'venta_id');
    }
}