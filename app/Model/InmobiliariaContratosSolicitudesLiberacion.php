<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaContratosSolicitudesLiberacion extends Model
{
    protected $table = 'contratos_solicitudes_liberaciones_inmobiliaria';
    protected $fillable = ['contrato_id', 'monto_solicitado', 'comentarios', 'usuario_pendiente_id'];

    public function contrato()
    {
        return $this->belongsTo(InmobiliariaContrato::class, 'contrato_id');
    }

    public function buscar()
    {
        $query = $this->select(
            'contratos_solicitudes_liberaciones_inmobiliaria.*',
            'u.nombre AS solicitante'
        )
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'contratos_solicitudes_liberaciones_inmobiliaria.usuario_pendiente_id');

        if (!empty($datos['contrato_id'])) {
            $query = $query->where('contrato_id', $datos['contrato_id']);
        }

        if ($datos['estado'] != "Todos") {
            $query = $query->where('estado', $datos['estado']);
        }

        return $query->get();
    }
}