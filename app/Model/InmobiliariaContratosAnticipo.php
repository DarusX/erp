<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaContratosAnticipo extends Model
{
    protected $table = 'contratos_anticipos_inmobiliaria';
    protected $fillable = ['contrato_id', 'monto_solicitado', 'comentarios', 'usuario_pendiente_id'];

    public function contrato()
    {
        return $this->belongsTo(InmobiliariaContrato::class, 'contrato_id');
    }

    public function buscar($datos)
    {
        $query = $this->select(
            'contratos_anticipos_inmobiliaria.*',
            'u.nombre AS solicitante'
        )
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'contratos_anticipos_inmobiliaria.usuario_pendiente_id');

        if (!empty($datos['contrato_id'])) {
            $query = $query->where('contrato_id', $datos['contrato_id']);
        }

        if ($datos['estado'] != "Todos") {
            $query = $query->where('estado', $datos['estado']);
        }

        if ($datos['pagado'] != "Todos") {
            $query = $query->where('pagado', $datos['pagado']);
        }

        return $query->get();
    }
}