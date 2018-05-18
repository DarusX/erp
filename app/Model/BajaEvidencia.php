<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BajaEvidencia extends Model
{
    protected $table = 'agr_baja_evidencia';

    protected $fillable = [
        'id',
        'animal_baja_id',
        'imagen',
        'usuario_id'
    ];

    public function buscar($datos){

        $query = $this->select('imagen');

        if(!empty($datos['id'])){
            $query->where('animal_baja_id', $datos['id']);
        }

        return $query->get();

    }
}
