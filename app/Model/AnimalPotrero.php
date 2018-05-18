<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AnimalPotrero extends Model
{
    protected $table = 'agr_animal_potrero';

    protected $fillable = [
        'id',
        'animal_id',
        'potrero_id',
        'entrada',
        'salida'
    ];

    public function reporte($datos){

        $query = $this->leftJoin('agr_potrero as p', 'p.id', '=', 'agr_animal_potrero.potrero_id');
        $query->select(
            'agr_animal_potrero.*',
            'p.potrero'
        );

        if(!empty($datos['animal_id'])){
            $query->where('animal_id', $datos['animal_id']);
        }

        return $query->get();

    }
}
