<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaCatalogosManzana extends Model
{
    protected $table = 'catalogos_manzanas_inmobiliaria';
    protected $fillable = ['numero', 'proyecto_id'];

    public function buscar($datos)
    {
        $query = $this->select(
            'catalogos_manzanas_inmobiliaria.*',
            'cpi.nombre AS proyecto'
        )
            ->leftJoin('catalogos_proyectos_inmobiliaria AS cpi', 'cpi.id', '=', 'catalogos_manzanas_inmobiliaria.proyecto_id');

        if (!empty($datos['numero'])) {
            $query->where('numero', $datos['numero']);
        }

        if (!empty($datos['proyecto_id'])) {
            $query->where('proyecto_id', $datos['proyecto_id']);
        }

        return $query->get();
    }

    public function inmuebles(){
        return $this->hasMany(InmobiliariaInmueble::class, 'manzana', 'numero');
    }
}