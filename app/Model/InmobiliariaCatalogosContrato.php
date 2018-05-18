<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaCatalogosContrato extends Model
{
    protected $table = 'catalogos_contratos_inmobiliaria';
    protected $fillable = ['nombre', 'slug', 'proyecto_id', 'usuario_id'];

    public function getNombreAttribute($value)
    {
        return $this->attributes['nombre'] = $value;
    }

    public function slug()
    {
        return $this->attributes['slug'] = str_slug($this->nombre);
    }

    public function buscar($datos)
    {
        $query = $this;
        if (!empty($datos['nombre'])) {
            $query->where('nombre', 'LIKE', '%' . $datos['nombre'] . '%');
        }
        return $query->get();
    }
}
