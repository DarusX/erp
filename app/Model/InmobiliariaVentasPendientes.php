<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaVentasPendientes extends Model
{
    protected $table = 'ventas_pendientes_inmobiliaria';

    public function buscar(array $datos = null)
    {
        $query = $this->select(
            'ventas_pendientes_inmobiliaria.*',
            'u.nombre AS usuario'
        )
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'ventas_pendientes_inmobiliaria.usuario_id');

        if (isset($datos['venta_id'])) {
            $query = $query->where('venta_id', $datos['venta_id']);
        }

        return $query->get();
    }
}