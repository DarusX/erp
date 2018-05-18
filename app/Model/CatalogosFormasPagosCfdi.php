<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CatalogosFormasPagosCfdi extends Model
{
    protected $table = 'catalogos_formas_pagos_cfdi';
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

        if (!empty($parametros['facturacion'])) {
            $query = $query->where('clave', 'like', '%' . $parametros['facturacion'] . '%')
                ->orWhere('descripcion', 'like', '%' . $parametros['facturacion'] . '%');
        }

        return $query->get();
    }
}