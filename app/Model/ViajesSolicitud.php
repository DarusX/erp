<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ViajesSolicitud extends Model
{
    protected $table = 'viajes_solicitudes';
    protected $fillable = ['tipo', 'pagado', 'vehiculo_viaje_orden_id', 'usuario_solicitud_id', 'comentarios'];

    public function buscar($datos)
    {
        $query = $this->from('viajes_solicitudes AS vs')
            ->select(
                'vs.*',
                'u.nombre AS usuario_solicitud'
            )
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'vs.usuario_solicitud_id');

        if (!empty($datos['fecha_inicial'])) {
            $query = $query->where($datos['tipo_fecha'], '>=', $datos['fecha_inicial']);
        }

        if (!empty($datos['fecha_final'])) {
            $query = $query->where($datos['tipo_fecha'], '<=', $datos['fecha_final']);
        }

        if (!empty($datos['vehiculo_viaje_orden_id'])) {
            $query = $query->where('vehiculo_viaje_orden_id', $datos['vehiculo_viaje_orden_id']);
        }

        if (!empty($datos['id'])) {
            $query = $query->where('id', $datos['id']);
        }

        if ($datos['tipo'] != "Todos") {
            $query = $query->where('tipo', $datos['tipo']);
        }

        if ($datos['estado'] != "Todos") {
            $query = $query->where('estado', $datos['estado']);
        }

        return $query->get();
    }
}
