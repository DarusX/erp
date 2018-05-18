<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaContratosPago extends Model
{
    protected $table = 'contratos_pagos_inmobiliaria';

    public function contrato()
    {
        return $this->belongsTo(InmobiliariaContrato::class, 'contrato_id');
    }
}
