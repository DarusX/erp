<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionInmobiliaria extends Model
{
    protected $table = 'configuracion_inmobiliaria';
    protected $fillable = ['dias_tolerancia_pagos', 'nombre_banco', 'cuenta_banco', 'clabe_banco', 'monto_mantenimiento', 'dias_notificacion_entrega', 'plan_conekta', 'dias_garantia', 'porcentaje_materiales_garantia'];

    public function buscar()
    {
        $query = $this;
        return $query->get();
    }
}