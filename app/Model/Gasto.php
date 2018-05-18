<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $table = 'agr_gasto';

    protected $fillable = [
        'id',
        'gasto',
        'estatus'
    ];

    public  function buscar($datos){
        $query = $this;

        if(!empty($datos['gasto'])){
            return $query->where('gasto', 'LIKE', '%'.$datos['gasto'].'%')->get();
        }

        return $query->all();
    }
}
