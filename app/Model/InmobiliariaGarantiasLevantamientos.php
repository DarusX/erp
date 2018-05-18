<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaGarantiasLevantamientos extends Model
{
    protected $table = 'garantias_levantamientos_inmobiliaria';

    public function productos()
    {
        return $this->hasMany(InmobiliariaGarantiasProductos::class, 'garantia_levantamiento_id');
    }

    public function manoObra()
    {
        return $this->hasMany(InmobiliariaGarantiasManoObra::class, 'garantia_levantamiento_id');
    }

    public function buscar($datos)
    {
        $query = $this->select(
            'garantias_levantamientos_inmobiliaria.*',
            'u.nombre AS usuario_realizacion'
        )
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'garantias_levantamientos_inmobiliaria.usuario_id');

        if (isset($datos['garantia_id'])) {
            $query = $query->where('garantia_id', $datos['garantia_id']);
        }

        return $query->get();
    }
}