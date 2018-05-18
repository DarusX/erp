<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaCatalogosProyecto extends Model
{
    protected $table = 'catalogos_proyectos_inmobiliaria';
    protected $fillable = ['nombre', 'usuario_id'];

    public function buscar($datos)
    {
        $query = $this;
        if (!empty($datos['nombre'])) {
            $query->where('nombre', 'LIKE', '%' . $datos['nombre'] . '%');
        }
        return $query->get();
    }

    public function tipoContratos()
    {
        return $this->hasMany(InmobiliariaCatalogosContrato::class, 'proyecto_id');
    }

    public function contratos()
    {
        return $this->hasMany(InmobiliariaContrato::class, 'proyecto_id');
    }

    public function manzanas()
    {
        return $this->hasMany(InmobiliariaCatalogosManzana::class, 'proyecto_id');
    }
}