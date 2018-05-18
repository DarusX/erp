<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaVentasNotificacionesAvaluo extends Model
{
    protected $table = 'ventas_notificaciones_avaluos_inmobiliaria';
    protected $fillable = ['venta_id', 'fecha_estimada', 'observaciones', 'fecha_hora_notificacion', 'usuario_notificacion_id', 'estado'];

    public function buscar($datos)
    {
        $query = $this->select(
            'ventas_notificaciones_avaluos_inmobiliaria.*',
            'i.nombre AS inmueble'
        )
            ->leftJoin('ventas_inmobiliaria AS v', 'v.id', '=', 'ventas_notificaciones_avaluos_inmobiliaria.venta_id')
            ->leftJoin('inmuebles_inmobiliaria AS i', 'i.id', '=', 'v.inmueble_id')
            ->whereIn('v.estado', ['En Proceso', 'Finalizado']);

        if (!empty($datos['id'])) {
            $query->addSelect(
                'vnaci.*',
                'u.nombre AS procesado',
                'u2.nombre AS autorizado'
            )
                ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'ventas_notificaciones_avaluos_inmobiliaria.usuario_proceso_id')
                ->leftJoin('usuarios AS u2', 'u2.id_usuario', '=', 'ventas_notificaciones_avaluos_inmobiliaria.usuario_finalizado_id')
                ->leftJoin('ventas_notificaciones_avaluos_comentarios_inmobiliaria AS vnaci', 'vnaci.notificacion_avaluo_id', '=', 'ventas_notificaciones_avaluos_inmobiliaria.id')
                ->where('ventas_notificaciones_avaluos_inmobiliaria.id', $datos['id']);
        }

        return $query->get();
    }
}