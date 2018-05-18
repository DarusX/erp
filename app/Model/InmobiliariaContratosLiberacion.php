<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaContratosLiberacion extends Model
{
    protected $table = 'contratos_liberaciones_inmobiliaria';

    public function buscar($datos)
    {
        $query = $this->select(
            'contratos_liberaciones_inmobiliaria.*',
            'ci.subtitulo AS contrato',
            'u.nombre AS usuario'
        )
            ->leftJoin('contratos_inmobiliaria AS ci', 'ci.id', '=', 'contratos_liberaciones_inmobiliaria.contrato_id')
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'contratos_liberaciones_inmobiliaria.usuario_id');

        if ($datos['contrato_id'] == "Todos") {
            $proyecto = InmobiliariaCatalogosProyecto::find($datos['proyecto_id']);
            $contratos = $proyecto->contratos()->lists('id');
            $query = $query->whereIn('contrato_id', $contratos);
        } else {
            $query = $query->where('contrato_id', $datos['contrato_id']);
        }

        if (!empty($datos['id'])) {
            $query = $query->where('contratos_liberaciones_inmobiliaria.id', $datos['id']);
        }

        return $query->get();
    }

    public function contrato()
    {
        return $this->belongsTo(InmobiliariaContrato::class, 'contrato_id');
    }
}