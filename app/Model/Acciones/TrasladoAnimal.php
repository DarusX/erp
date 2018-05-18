<?php

namespace App\Model\Acciones;

use Illuminate\Database\Eloquent\Model;

class TrasladoAnimal extends Model
{
    protected $table = 'agr_traslado_animal';

    protected $fillable = [
        'id',
        'traslado_id',
        'animal_id',
        'potrero_origen_id',
        'potrero_destino_id'
    ];

    public function reporte($datos)
    {
        $query = $this->leftJoin("agr_animal as a", 'a.id', '=', 'agr_traslado_animal.animal_id');
        $query->leftJoin("agr_potrero as po", 'po.id', '=', 'agr_traslado_animal.potrero_origen_id');
        $query->leftJoin("agr_potrero as pd", 'pd.id', '=', 'agr_traslado_animal.potrero_destino_id');

        $query->select(
            'agr_traslado_animal.*',
            'a.numero',
            'a.raza',
            'po.potrero as potrero_origen',
            'pd.potrero as potrero_destino'
        );

        if(!empty($datos['id'])){
            $query->where('traslado_id', $datos['id']);
        }

        return $query->get();
    }
}
