<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaGarantiasManoObra extends Model
{
    protected $table = 'garantias_mano_obra_inmobiliaria';

    public function buscar($datos)
    {
        $query = $this->select(
            'garantias_mano_obra_inmobiliaria.*',
            'cmoi.nombre'
        )
            ->leftJoin('garantias_levantamientos_inmobiliaria AS gli', 'gli.id', '=', 'garantias_mano_obra_inmobiliaria.garantia_levantamiento_id')
            ->leftJoin('catalogos_mano_obra_inmobiliaria AS cmoi', 'cmoi.id', '=', 'garantias_mano_obra_inmobiliaria.mano_obra_id');

        if (isset($datos['levantamiento_id'])) {
            $query = $query->where('garantias_mano_obra_inmobiliaria.garantia_levantamiento_id', $datos['levantamiento_id']);
        }

        if (isset($datos['estado'])) {
            $query = $query->where('gli.estado', $datos['estado']);
        }

        return $query->get();
    }
}