<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AnimalEventoDescripcion extends Model
{
    protected $table = 'agr_animal_evento_descripcion';

    protected $fillable = [
        'id',
        'animal_id',
        'evento_id',
        'animal_evento_id',
        'rancho_id',
        'potrero_id',
        'fecha'
    ];

    public function reporte($datos)
    {

        $query = $this->leftJoin('agr_evento as e', 'e.id', '=', 'agr_animal_evento_descripcion.evento_id');
        $query->leftJoin('agr_rancho as r', 'r.id', '=', 'agr_animal_evento_descripcion.rancho_id');
        $query->leftJoin('agr_potrero as p', 'p.id', '=', 'agr_animal_evento_descripcion.potrero_id');
        $query->leftJoin('agr_animal as a', 'a.id', '=', 'agr_animal_evento_descripcion.animal_id');

        $query->select(
            'agr_animal_evento_descripcion.*',
            'e.evento',
            'r.rancho',
            'p.potrero',
            'a.id',
            'a.numero'
        );

        if (!empty($datos['animal_id'])) {
            $query->where('animal_id', $datos['animal_id'])->where('a.status', 'Activo');
        }
        if (!empty($datos['evento_id'])) {
            $query->where('evento_id', $datos['evento_id'])->where('e.status', 'Activo');
        }
        if (!empty($datos['rancho_id'])) {
            $query->where('agr_animal_evento_descripcion.rancho_id', $datos['rancho_id'])->where('r.status', 'Activo');
        }
        if (!empty($datos['potrero_id'])) {
            $query->where('agr_animal_evento_descripcion.potrero_id', $datos['potrero_id'])->where('p.status', 'Activo');
        }
        if (!empty($datos['fecha_ini'])) {
            $query->where('fecha', '>=', $datos['fecha_ini']);
        }
        if (!empty($datos['fecha_fin'])) {
            $query->where('fecha', '<=', $datos['fecha_fin']);
        }
        if (!empty($datos['numero'])) {
            $query->where('a.numero', 'LIKE', '%'.$datos['numero'].'%');
        }

        return $query->get();

    }

    public function evento(){
        return $this->hasOne(Evento::class,'id', 'evento_id');
    }
}