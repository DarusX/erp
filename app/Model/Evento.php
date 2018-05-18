<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'agr_evento';

    protected $fillable = [
        'id',
        'evento',
        'status'
    ];

    public function buscar($datos){

        $query = $this;

        if(!empty($datos['evento'])){
            return $query->where('evento', 'LIKE', '%'.$datos['evento'].'%')->get();
        }

        return $query->all();

    }
}
