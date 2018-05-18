<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Rancho extends Model
{
    protected $table = 'agr_rancho';

    protected $fillable = [
        'id',
        'rancho',
        'status'
    ];

    public  function buscar($datos){

        $query = $this->select("agr_rancho.*");

        if(!empty($datos['rancho'])){
            return $query->where('rancho', 'LIKE', '%'.$datos['rancho'].'%');
        }

        if(!empty($datos['status'])){
            $query->where('status', $datos['status']);
        }

        if(!empty($datos["rancho_id"])){
            $query->where("id", $datos["rancho_id"]);
            return $query->first();
        }

        //dd($query->toSql());

        return $query->get();
    }
}
