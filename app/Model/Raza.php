<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Raza extends Model
{
    protected $table = 'agr_razas';

    protected $fillable = [
        'id',
        'raza',
        'categoria_id',
        'estatus'
    ];

    public function buscar($datos){

        $query = $this->leftJoin("agr_categorias_razas as cr", "cr.id", "=", "agr_razas.categoria_id");

        $query->select(
            "agr_razas.*",
            \DB::raw("ifnull(cr.nombre,'') as nombre")
        );

        if(!empty($datos['raza'])){
            return $query->where('raza', 'LIKE', '%'.$datos['raza'].'%')->get();
        }
        if(!empty($datos['categoria_id'])){
            if(count($datos["categoria_id"]) > 1){
                $query->whereIn('categoria_id', $datos['categoria_id'])->get();
            }else{
                $query->where("categoria_id", $datos["categoria_id"]);
            }

        }

        if(!empty($datos["raza_id"])){
            $query->where("agr_razas.id", $datos["raza_id"]);
            return $query->first();
        }

        return $query->get();
    }
}
