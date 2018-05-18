<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Potrero extends Model
{
    protected $table = 'agr_potrero';

    protected $fillable = [
        'potrero',
        'rancho_id',
        'status'
    ];

    public function buscar($datos){
        $query = $this->leftJoin('agr_rancho as r', 'r.id', '=', 'agr_potrero.rancho_id');

        if(!empty($datos['potrero'])){
            $query->where('potrero', 'LIKE', '%'.$datos['potrero'].'%');
        }
        if(!empty($datos['rancho_id'])){
            $query->where('rancho_id', $datos['rancho_id']);
        }

        $query->select(
            'agr_potrero.*',
            'r.rancho'
        );

        return $query->get();
    }

    public function rancho(){
        return $this->hasOne(Rancho::class,'id', 'rancho_id');
    }
}
