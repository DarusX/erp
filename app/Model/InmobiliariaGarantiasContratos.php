<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaGarantiasContratos extends Model
{
    protected $table = 'garantias_contratos_inmobiliaria';

    public function contrato()
    {
        return $this->belongsTo(InmobiliariaContrato::class, 'contrato_id');
    }
}