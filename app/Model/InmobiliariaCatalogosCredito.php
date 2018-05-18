<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaCatalogosCredito extends Model
{
    protected $table = 'catalogos_creditos_inmobiliaria';
    protected $fillable = ['nombre'];

    public function buscar($datos)
    {
        $query = $this;
        if (!empty($datos['nombre'])) {
            $query->where('nombre', 'LIKE', '%' . $datos['nombre'] . '%');
        }
        if (!empty($datos['id'])) {
            return $query->where('id', $datos['id'])->first();
        }
        return $query->get();
    }

    public function formatos()
    {
        return $this->hasMany(InmobiliariaCatalogosCreditoDocumento::class, 'credito_id');
    }
}