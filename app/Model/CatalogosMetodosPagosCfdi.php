<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CatalogosMetodosPagosCfdi extends Model
{
    protected $table = 'catalogos_metodos_pagos_cfdi';
    protected $fillable = ['clave', 'descripcion'];

    public function buscar($parametros)
    {
        $query = $this->select('id', 'clave', 'descripcion');
        if (!empty($parametros['clave'])) {
            $query = $query->where('clave', $parametros['clave']);
        }
        if (!empty($parametros['descripcion'])) {
            $query = $query->where('descripcion', 'LIKE', '%' . $parametros['descripcion'] . '%');
        }
        return $query->get();
    }
}