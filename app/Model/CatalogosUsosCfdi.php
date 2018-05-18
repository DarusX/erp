<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CatalogosUsosCfdi extends Model
{
    protected $table = 'catalogos_usos_cfdi';
    protected $fillable = ['clave', 'descripcion'];

    public function buscar($parametros)
    {
        $query = $this->select('id', 'clave', 'descripcion', 'concepto');
        if (!empty($parametros['clave'])) {
            $query = $query->where('clave', $parametros['clave']);
        }
        if (!empty($parametros['descripcion'])) {
            $query = $query->where('descripcion', 'LIKE', $parametros['descripcion'] . '%');
        }
        if (!empty($parametros['concepto'])) {
            $query = $query->where('concepto', $parametros['concepto']);
        }
        return $query->get();
    }
}