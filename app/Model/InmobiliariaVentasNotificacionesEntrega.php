<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaVentasNotificacionesEntrega extends Model
{
    protected $table = 'ventas_notificaciones_entregas_inmobiliaria';
    protected $fillable = ['venta_id', 'fecha_entrega', 'observaciones', 'fecha_hora_notificacion', 'usuario_notificacion_id', 'estado'];

    public function buscar()
    {
        $query = $this->select(
            'ventas_notificaciones_entregas_inmobiliaria.*',
            'i.nombre AS inmueble'
        )
            ->leftJoin('ventas_inmobiliaria AS v', 'v.id', '=', 'ventas_notificaciones_entregas_inmobiliaria.venta_id')
            ->leftJoin('inmuebles_inmobiliaria AS i', 'i.id', '=', 'v.inmueble_id');

        return $query->get();
    }

    public function venta(){
        return $this->belongsTo(InmobiliariaVentas::class, 'venta_id');
    }
}